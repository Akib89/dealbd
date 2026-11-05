<?php
namespace Tests\Feature;

use App\Models\{Category, Deal, Store, User};
use App\Notifications\MatchingDealNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DealFlowTest extends TestCase
{
    use RefreshDatabase;

    private function seedLookup(): array
    {
        $category=Category::create(['name'=>'Electronics','slug'=>'electronics']);
        $store=Store::create(['name'=>'Test Store','slug'=>'test-store']);
        return [$category,$store];
    }

    private function createDeal(string $status='published'): Deal
    {
        [$category,$store]=$this->seedLookup();
        return Deal::create([
            'user_id'=>User::factory()->create()->id,
            'category_id'=>$category->id,
            'store_id'=>$store->id,
            'title'=>'Example promotional wireless mouse',
            'slug'=>'test-mouse-'.uniqid(),
            'description'=>'A demonstration offer used only inside the test suite.',
            'deal_url'=>'https://example.com/offer',
            'price'=>490,
            'original_price'=>800,
            'discount_percent'=>39,
            'status'=>$status,
            'published_at'=>$status==='published'?now():null,
        ]);
    }

    public function test_public_can_browse_approved_but_not_pending_deals(): void
    {
        $published=$this->createDeal();
        $this->get('/')->assertOk()->assertSee($published->title);
        $this->get(route('deals.show',$published->slug))->assertOk();
        $pending=Deal::create(array_merge($published->only([
            'user_id','category_id','store_id','title','description','deal_url','price','original_price','discount_percent'
        ]),['slug'=>'pending-test','status'=>'pending']));
        $this->get(route('deals.show',$pending->slug))->assertNotFound();
    }

    public function test_vote_can_be_toggled_without_duplicate_score(): void
    {
        $deal=$this->createDeal();
        $voter=User::factory()->create();
        $this->actingAs($voter)->post(route('deals.vote',$deal->slug),['value'=>1])->assertRedirect();
        $this->assertEquals(1,$deal->fresh()->score);
        $this->actingAs($voter)->post(route('deals.vote',$deal->slug),['value'=>1])->assertRedirect();
        $this->assertEquals(0,$deal->fresh()->score);
    }

    public function test_regular_member_cannot_approve_a_deal(): void
    {
        $pending=$this->createDeal('pending');
        $this->actingAs(User::factory()->create())
            ->post(route('admin.moderate',$pending->id),['action'=>'approve'])
            ->assertForbidden();
    }

    public function test_approving_deal_dispatches_matching_alert_notification(): void
    {
        Notification::fake();
        $deal=$this->createDeal('pending');
        $subscriber=User::factory()->create();
        $subscriber->alerts()->create(['keyword'=>'wireless','category_id'=>$deal->category_id,'active'=>true]);
        $admin=User::factory()->create();
        $admin->forceFill(['role'=>'admin'])->save();
        $this->actingAs($admin)->post(route('admin.moderate',$deal->id),['action'=>'approve'])
            ->assertRedirect();
        $this->assertEquals('published',$deal->fresh()->status);
        Notification::assertSentTo($subscriber,MatchingDealNotification::class);
    }

    public function test_deal_links_reject_javascript_urls(): void
    {
        [$category,$store]=$this->seedLookup();
        $this->actingAs(User::factory()->create())->post(route('deals.store'),[
            'title'=>'A definitely invalid link',
            'description'=>'This test checks URL input validation for a deal.',
            'category_id'=>$category->id,'store_id'=>$store->id,
            'deal_url'=>'javascript:alert(1)','price'=>20,
        ])->assertSessionHasErrors('deal_url');
    }
}
