<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_sent_to_the_admin_panel_after_login(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.index'));
    }

    public function test_admin_can_see_users_and_their_posts(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $member = User::factory()->create(['name' => 'Posting Member']);
        $deal = $this->createDeal($member, 'Member posted deal');

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk()
            ->assertSee('Posting Member')
            ->assertSee($deal->title)
            ->assertSee(route('admin.users.edit', $member))
            ->assertSee(route('deals.edit', $deal->slug));
    }

    public function test_admin_can_update_another_user(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $member = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $member), [
                'name' => 'Updated Member',
                'email' => 'updated@example.com',
                'role' => 'moderator',
            ])
            ->assertRedirect(route('admin.users.edit', $member));

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Updated Member',
            'email' => 'updated@example.com',
            'role' => 'moderator',
        ]);
    }

    public function test_member_cannot_access_admin_user_management(): void
    {
        $member = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($member)
            ->get(route('admin.users.edit', $otherUser))
            ->assertForbidden();
    }

    public function test_admin_can_update_a_post_without_resetting_its_status(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $member = User::factory()->create();
        $deal = $this->createDeal($member, 'Original post title');

        $this->actingAs($admin)
            ->put(route('deals.update', $deal->slug), [
                'title' => 'Updated post title',
                'description' => 'An updated and sufficiently detailed description for the deal.',
                'category_id' => $deal->category_id,
                'store_id' => $deal->store_id,
                'deal_url' => 'https://example.com/updated-deal',
                'price' => 449,
            ])
            ->assertRedirect(route('deals.show', $deal->slug));

        $this->assertDatabaseHas('deals', [
            'id' => $deal->id,
            'title' => 'Updated post title',
            'status' => 'published',
        ]);
    }

    private function createDeal(User $user, string $title): Deal
    {
        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $store = Store::create(['name' => 'Test Store', 'slug' => 'test-store']);

        return Deal::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'store_id' => $store->id,
            'title' => $title,
            'slug' => 'member-posted-deal',
            'description' => 'A detailed test deal description for admin management.',
            'deal_url' => 'https://example.com/deal',
            'price' => 499,
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
