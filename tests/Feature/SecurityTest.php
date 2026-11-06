<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_responses_include_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_debug_mode_and_session_encryption_are_safe(): void
    {
        $this->assertFalse(config('app.debug'));
        $this->assertTrue(config('session.encrypt'));
        $this->assertTrue(config('session.http_only'));
        $this->assertSame('lax', config('session.same_site'));
    }

    public function test_admin_pages_require_authentication(): void
    {
        $this->get(route('admin.index'))->assertRedirect(route('login'));
    }

    public function test_user_cannot_delete_another_users_alert(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $alert = $owner->alerts()->create(['keyword' => 'laptop', 'active' => true]);

        $this->actingAs($attacker)
            ->delete(route('alerts.destroy', $alert))
            ->assertForbidden();

        $this->assertDatabaseHas('deal_alerts', ['id' => $alert->id, 'user_id' => $owner->id]);
    }

    public function test_user_content_is_escaped_in_deal_feed(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $store = Store::create(['name' => 'Test Store', 'slug' => 'test-store']);
        Deal::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'store_id' => $store->id,
            'title' => '<script>alert(1)</script>',
            'slug' => 'escaped-title',
            'description' => '<img src=x onerror=alert(1)>',
            'deal_url' => 'https://example.com/deal',
            'price' => 100,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }

    public function test_login_is_rate_limited_by_account_and_ip(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'unknown@example.com',
                'password' => 'incorrect-password',
            ])->assertRedirect();
        }

        $this->post(route('login.store'), [
            'email' => 'unknown@example.com',
            'password' => 'incorrect-password',
        ])->assertTooManyRequests();
    }
}
