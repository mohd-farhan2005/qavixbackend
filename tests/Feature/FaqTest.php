<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'name' => 'Qavix Admin',
            'email' => 'admin@qavix.com',
            'password' => Hash::make('qavix@2026'),
            'role' => 'admin',
        ]);
    }

    public function test_public_api_returns_active_faqs_and_schema(): void
    {
        Faq::create([
            'question' => 'What is Qavix?',
            'answer' => 'Qavix is a high performance digital agency platform.',
            'category' => 'General',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Hidden draft question?',
            'answer' => 'This should not appear publicly.',
            'category' => 'Drafts',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/faqs');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 1,
            ])
            ->assertJsonPath('data.0.question', 'What is Qavix?')
            ->assertJsonPath('schema.@type', 'FAQPage')
            ->assertJsonPath('schema.mainEntity.0.name', 'What is Qavix?')
            ->assertJsonPath('schema.mainEntity.0.acceptedAnswer.text', 'Qavix is a high performance digital agency platform.');
    }

    public function test_public_api_can_filter_faqs_by_category(): void
    {
        Faq::create([
            'question' => 'React FAQ Question',
            'answer' => 'React is awesome.',
            'category' => 'React',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'SEO FAQ Question',
            'answer' => 'SEO drives organic traffic.',
            'category' => 'SEO',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/faqs?category=React');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 1,
            ])
            ->assertJsonPath('data.0.question', 'React FAQ Question');
    }

    public function test_unauthenticated_user_cannot_access_admin_faqs_page(): void
    {
        $response = $this->get('/admin/faqs');
        $response->assertStatus(302)->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_faqs_page(): void
    {
        Faq::create([
            'question' => 'Do you build web apps?',
            'answer' => 'Yes, using React and Laravel.',
            'category' => 'Services',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/faqs');

        $response->assertStatus(200)
            ->assertSee('Frequently Asked Questions (FAQs)', false)
            ->assertSee('Do you build web apps?', false);
    }

    public function test_admin_can_store_faq_via_web_form(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'question' => 'How secure is the platform?',
            'answer' => 'All APIs are secured with Sanctum tokens and CSRF protection.',
            'category' => 'Security',
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('faqs', [
            'question' => 'How secure is the platform?',
            'category' => 'Security',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_faq_via_web_form(): void
    {
        $faq = Faq::create([
            'question' => 'Original Question',
            'answer' => 'Original Answer',
            'category' => 'General',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/faqs/{$faq->id}", [
            'question' => 'Updated Question Headline',
            'answer' => 'Updated Answer content with more details.',
            'category' => 'Updated Category',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Updated Question Headline',
            'category' => 'Updated Category',
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_toggle_faq_active_status(): void
    {
        $faq = Faq::create([
            'question' => 'Toggle Me',
            'answer' => 'Toggle Status Test',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/faqs/{$faq->id}/toggle-active");

        $response->assertStatus(302);
        $this->assertFalse($faq->fresh()->is_active);

        $response2 = $this->actingAs($this->admin)->post("/admin/faqs/{$faq->id}/toggle-active");
        $response2->assertStatus(302);
        $this->assertTrue($faq->fresh()->is_active);
    }

    public function test_admin_can_delete_faq(): void
    {
        $faq = Faq::create([
            'question' => 'To be deleted',
            'answer' => 'Will be gone',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/faqs/{$faq->id}");

        $response->assertStatus(302)
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_admin_can_manage_faqs_via_sanctum_api(): void
    {
        Sanctum::actingAs($this->admin);

        // 1. Create via API
        $createRes = $this->postJson('/api/admin/faqs', [
            'question' => 'API Created FAQ',
            'answer' => 'Created via protected JSON endpoint.',
            'category' => 'API',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $createRes->assertStatus(201)
            ->assertJsonPath('data.question', 'API Created FAQ');

        $faqId = $createRes->json('data.id');

        // 2. Fetch list
        $listRes = $this->getJson('/api/admin/faqs');
        $listRes->assertStatus(200)
            ->assertJsonPath('total', 1);

        // 3. Update via API
        $updateRes = $this->putJson("/api/admin/faqs/{$faqId}", [
            'question' => 'API Updated FAQ',
            'answer' => 'Updated via JSON endpoint.',
        ]);

        $updateRes->assertStatus(200)
            ->assertJsonPath('data.question', 'API Updated FAQ');

        // 4. Toggle active via API
        $toggleRes = $this->postJson("/api/admin/faqs/{$faqId}/toggle-active");
        $toggleRes->assertStatus(200)
            ->assertJsonPath('data.is_active', false);

        // 5. Delete via API
        $deleteRes = $this->deleteJson("/api/admin/faqs/{$faqId}");
        $deleteRes->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('faqs', ['id' => $faqId]);
    }
}

