<?php

namespace Tests\Feature;

use App\Models\LaunchpadLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LaunchpadTest extends TestCase
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

    public function test_public_can_submit_launchpad_application(): void
    {
        $payload = [
            'full_name' => 'Kunal Kapoor',
            'email' => 'kunal@growthbox.io',
            'whatsapp_number' => '+91 98765 12345',
            'startup_name' => 'GrowthBox',
            'industry' => 'Marketing Tech',
        ];

        $response = $this->postJson('/api/launchpad', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'full_name' => 'Kunal Kapoor',
                    'email' => 'kunal@growthbox.io',
                    'startup_name' => 'GrowthBox',
                    'industry' => 'Marketing Tech',
                ],
            ]);

        $this->assertDatabaseHas('launchpad_leads', [
            'email' => 'kunal@growthbox.io',
            'startup_name' => 'GrowthBox',
            'status' => 'new',
            'is_read' => 0,
        ]);
    }

    public function test_public_launchpad_supports_field_aliases(): void
    {
        $payload = [
            'name' => 'Devendra Singh',
            'email' => 'dev@hyperstack.in',
            'phone' => '+91 88776 65544',
            'startup' => 'HyperStack',
            'industry' => 'Developer Tools',
        ];

        $response = $this->postJson('/api/launchpad', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.full_name', 'Devendra Singh')
            ->assertJsonPath('data.whatsapp_number', '+91 88776 65544');

        $this->assertDatabaseHas('launchpad_leads', [
            'full_name' => 'Devendra Singh',
            'whatsapp_number' => '+91 88776 65544',
            'startup_name' => 'HyperStack',
        ]);
    }

    public function test_launchpad_submission_validates_required_fields(): void
    {
        $response = $this->postJson('/api/launchpad', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['full_name', 'email', 'whatsapp_number']);
    }

    public function test_unauthenticated_user_cannot_access_launchpad_admin_panel(): void
    {
        $response = $this->get('/admin/launchpad');
        $response->assertStatus(302)->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_launchpad_leads_in_dashboard(): void
    {
        LaunchpadLead::create([
            'full_name' => 'Vikram Seth',
            'email' => 'vikram@pulse.ai',
            'whatsapp_number' => '+91 99887 76655',
            'startup_name' => 'Pulse AI',
            'industry' => 'HealthTech',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/launchpad');

        $response->assertStatus(200)
            ->assertSee('Launchpad Leads & Applications', false)
            ->assertSee('Vikram Seth', false)
            ->assertSee('Pulse AI', false);
    }

    public function test_admin_can_view_single_application_and_marks_read(): void
    {
        $lead = LaunchpadLead::create([
            'full_name' => 'Ananya Roy',
            'email' => 'ananya@zenith.co',
            'whatsapp_number' => '+91 90000 11111',
            'startup_name' => 'Zenith Pay',
            'industry' => 'Fintech',
            'status' => 'new',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/launchpad/{$lead->id}");

        $response->assertStatus(200)
            ->assertSee('Ananya Roy', false)
            ->assertSee('Zenith Pay', false);

        $this->assertTrue($lead->fresh()->is_read);
    }

    public function test_admin_can_update_application_status(): void
    {
        $lead = LaunchpadLead::create([
            'full_name' => 'Pooja Iyer',
            'email' => 'pooja@cloudsync.io',
            'whatsapp_number' => '+91 91122 33445',
            'startup_name' => 'CloudSync',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/launchpad/{$lead->id}/status", [
            'status' => 'qualified',
            'notes' => 'Great product demo, moving to onboarding.',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('launchpad_leads', [
            'id' => $lead->id,
            'status' => 'qualified',
            'notes' => 'Great product demo, moving to onboarding.',
        ]);
    }

    public function test_admin_can_delete_application(): void
    {
        $lead = LaunchpadLead::create([
            'full_name' => 'Tarun Verma',
            'email' => 'tarun@spam.com',
            'whatsapp_number' => '1234567890',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/launchpad/{$lead->id}");

        $response->assertStatus(302)
            ->assertRedirect(route('admin.launchpad.index'));

        $this->assertDatabaseMissing('launchpad_leads', ['id' => $lead->id]);
    }

    public function test_admin_can_manage_launchpad_via_sanctum_api(): void
    {
        Sanctum::actingAs($this->admin);

        $lead = LaunchpadLead::create([
            'full_name' => 'API Founder',
            'email' => 'founder@api.com',
            'whatsapp_number' => '+91 90909 09090',
            'startup_name' => 'APICorp',
            'industry' => 'SaaS',
            'status' => 'new',
            'is_read' => false,
        ]);

        // 1. List
        $listRes = $this->getJson('/api/admin/launchpad');
        $listRes->assertStatus(200)
            ->assertJsonPath('meta.total', 1);

        // 2. Show
        $showRes = $this->getJson("/api/admin/launchpad/{$lead->id}");
        $showRes->assertStatus(200)
            ->assertJsonPath('data.full_name', 'API Founder');
        $this->assertTrue($lead->fresh()->is_read);

        // 3. Update status
        $updateRes = $this->patchJson("/api/admin/launchpad/{$lead->id}/status", [
            'status' => 'contacted',
            'notes' => 'Discussed via API call.',
        ]);
        $updateRes->assertStatus(200)
            ->assertJsonPath('data.status', 'contacted');

        // 4. Delete
        $deleteRes = $this->deleteJson("/api/admin/launchpad/{$lead->id}");
        $deleteRes->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('launchpad_leads', ['id' => $lead->id]);
    }
}

