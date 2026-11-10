<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientAndIndustryTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Industry $fintech;
    protected Industry $aiSaas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'username' => 'admin',
            'name' => 'Qavyx Admin',
            'email' => 'admin@qavyx.com',
            'password' => Hash::make('qavyx@2026'),
            'role' => 'admin',
        ]);

        $this->fintech = Industry::create([
            'name' => 'Fintech & Web3',
            'slug' => 'fintech-web3',
            'description' => 'Financial software and crypto rails.',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->aiSaas = Industry::create([
            'name' => 'AI & SaaS',
            'slug' => 'ai-saas',
            'description' => 'Artificial intelligence and cloud platforms.',
            'order' => 2,
            'is_active' => true,
        ]);
    }

    public function test_public_can_fetch_active_industries(): void
    {
        Industry::create([
            'name' => 'Draft Industry',
            'slug' => 'draft-industry',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/industries');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('count', 2)
            ->assertJsonPath('data.0.slug', 'fintech-web3')
            ->assertJsonPath('data.1.slug', 'ai-saas');
    }

    public function test_public_can_fetch_industries_with_embedded_clients(): void
    {
        Client::create([
            'name' => 'Stripe',
            'industry_id' => $this->fintech->id,
            'logo' => 'https://example.com/stripe.svg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/industries?with_clients=1');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.active_clients.0.name', 'Stripe');
    }

    public function test_public_can_fetch_single_industry_by_slug(): void
    {
        $response = $this->getJson("/api/industries/{$this->fintech->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Fintech & Web3');
    }

    public function test_public_can_fetch_clients_and_filter_by_industry(): void
    {
        Client::create([
            'name' => 'Stripe Payments',
            'industry_id' => $this->fintech->id,
            'logo' => 'https://example.com/stripe.svg',
            'website_url' => 'https://stripe.com',
            'featured' => true,
            'order' => 1,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'OpenAI Cloud',
            'industry_id' => $this->aiSaas->id,
            'logo' => 'https://example.com/openai.svg',
            'featured' => false,
            'order' => 2,
            'is_active' => true,
        ]);

        // 1. Fetch all
        $allResponse = $this->getJson('/api/clients');
        $allResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('count', 2)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'industry_id',
                        'industry',
                        'logo_url',
                        'website_url',
                    ],
                ],
            ]);

        // 2. Filter by industry slug
        $filterResponse = $this->getJson('/api/clients?industry=fintech-web3');
        $filterResponse->assertStatus(200)
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.name', 'Stripe Payments')
            ->assertJsonPath('data.0.industry.slug', 'fintech-web3');

        // 3. Filter by featured
        $featuredResponse = $this->getJson('/api/clients?featured=1');
        $featuredResponse->assertStatus(200)
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.name', 'Stripe Payments');
    }

    public function test_admin_can_view_industries_and_clients_index_pages(): void
    {
        $indResponse = $this->actingAs($this->adminUser)->get('/admin/industries');
        $indResponse->assertStatus(200)
            ->assertSee('Industries')
            ->assertSee('Add Industry');

        $clientResponse = $this->actingAs($this->adminUser)->get('/admin/clients');
        $clientResponse->assertStatus(200)
            ->assertSee('Clients')
            ->assertSee('Add Client');
    }

    public function test_admin_can_create_industry_via_web_form(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/industries', [
            'name' => 'Healthcare & MedTech',
            'slug' => 'healthcare-medtech',
            'description' => 'Medical devices and hospital software.',
            'order' => 3,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/industries')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('industries', [
            'name' => 'Healthcare & MedTech',
            'slug' => 'healthcare-medtech',
        ]);
    }

    public function test_admin_can_create_client_with_industry_dropdown_via_web_form(): void
    {
        Storage::fake('public');
        $logoFile = UploadedFile::fake()->image('stripe-logo.png', 200, 80);

        $response = $this->actingAs($this->adminUser)->post('/admin/clients', [
            'name' => 'Stripe',
            'industry_id' => $this->fintech->id,
            'logo' => $logoFile,
            'website_url' => 'https://stripe.com',
            'description' => 'Payment gateway leader.',
            'featured' => '1',
            'order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/clients')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'name' => 'Stripe',
            'industry_id' => $this->fintech->id,
            'website_url' => 'https://stripe.com',
            'featured' => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_client_via_web_form(): void
    {
        $client = Client::create([
            'name' => 'Old Name',
            'industry_id' => $this->fintech->id,
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/clients/{$client->id}", [
            'name' => 'Updated Client Name',
            'industry_id' => $this->aiSaas->id,
            'website_url' => 'https://updated.com',
            'order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/clients')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Updated Client Name',
            'industry_id' => $this->aiSaas->id,
        ]);
    }

    public function test_admin_can_toggle_client_active_status(): void
    {
        $client = Client::create([
            'name' => 'Toggle Corp',
            'industry_id' => $this->fintech->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/clients/{$client->id}/toggle-active");

        $response->assertRedirect('/admin/clients');
        $this->assertFalse($client->fresh()->is_active);
    }

    public function test_admin_can_delete_client(): void
    {
        $client = Client::create([
            'name' => 'Delete Corp',
            'industry_id' => $this->fintech->id,
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/clients/{$client->id}");

        $response->assertRedirect('/admin/clients')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_admin_can_manage_clients_and_industries_via_sanctum_api(): void
    {
        $token = $this->adminUser->createToken('admin-test')->plainTextToken;

        // 1. Create Industry via API
        $indRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/industries', [
                'name' => 'E-Commerce & Retail',
                'description' => 'Retail digital stores.',
            ]);

        $indRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'E-Commerce & Retail');

        $newIndustryId = $indRes->json('data.id');

        // 2. Create Client under new industry via API
        $clientRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/clients', [
                'name' => 'Shopify Partner',
                'industry_id' => $newIndustryId,
                'website_url' => 'https://shopify.com',
                'featured' => true,
            ]);

        $clientRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Shopify Partner')
            ->assertJsonPath('data.industry.id', $newIndustryId);
    }
}
