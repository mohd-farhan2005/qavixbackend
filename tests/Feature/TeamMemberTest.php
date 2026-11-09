<?php

namespace Tests\Feature;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamMemberTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

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
    }

    public function test_public_can_fetch_active_team_members(): void
    {
        TeamMember::create([
            'name' => 'Zack Hill',
            'position' => 'Founder & CEO',
            'email' => 'zack@qavyx.com',
            'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d',
            'order' => 1,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Hidden Member',
            'position' => 'Secret Advisor',
            'email' => 'secret@qavyx.com',
            'photo' => null,
            'order' => 2,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/team');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.name', 'Zack Hill')
            ->assertJsonPath('data.0.position', 'Founder & CEO')
            ->assertJsonPath('data.0.email', 'zack@qavyx.com')
            ->assertJsonStructure([
                'success',
                'count',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'position',
                        'email',
                        'photo',
                        'photo_url',
                        'order',
                        'is_active',
                    ],
                ],
            ]);
    }

    public function test_public_can_fetch_single_team_member(): void
    {
        $member = TeamMember::create([
            'name' => 'Sarah Devine',
            'position' => 'Director of Projects',
            'email' => 'sarah@qavyx.com',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/team/{$member->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Sarah Devine')
            ->assertJsonPath('data.position', 'Director of Projects')
            ->assertJsonPath('data.email', 'sarah@qavyx.com');
    }

    public function test_admin_can_view_team_management_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/team');

        $response->assertStatus(200)
            ->assertSee('Team Members')
            ->assertSee('Add Team Member');
    }

    public function test_admin_can_create_team_member_via_web_form(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('zack.jpg', 600, 600);

        $response = $this->actingAs($this->adminUser)->post('/admin/team', [
            'name' => 'Zack Hill',
            'position' => 'Founder & CEO',
            'email' => 'zack@qavyx.com',
            'photo' => $file,
            'order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/team')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('team_members', [
            'name' => 'Zack Hill',
            'position' => 'Founder & CEO',
            'email' => 'zack@qavyx.com',
            'order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_team_member_via_web_form(): void
    {
        $member = TeamMember::create([
            'name' => 'Will Carter',
            'position' => 'Account Manager',
            'email' => 'will@qavyx.com',
            'order' => 4,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/team/{$member->id}", [
            'name' => 'Will Carter',
            'position' => 'Director of Accounts',
            'email' => 'will@qavyx.com',
            'order' => 4,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/team')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('team_members', [
            'id' => $member->id,
            'position' => 'Director of Accounts',
        ]);
    }

    public function test_admin_can_toggle_team_member_active_status(): void
    {
        $member = TeamMember::create([
            'name' => 'Calvin Bramlett',
            'position' => 'Creative & Design Director',
            'email' => 'calvin@qavyx.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/team/{$member->id}/toggle-active");

        $response->assertRedirect('/admin/team');
        $this->assertFalse($member->fresh()->is_active);
    }

    public function test_admin_can_delete_team_member(): void
    {
        $member = TeamMember::create([
            'name' => 'Temporary Member',
            'position' => 'Consultant',
            'email' => 'temp@qavyx.com',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/team/{$member->id}");

        $response->assertRedirect('/admin/team')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('team_members', [
            'id' => $member->id,
        ]);
    }

    public function test_admin_can_manage_team_via_sanctum_api(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // Create
        $createResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/team', [
                'name' => 'Arlton Lowry',
                'position' => 'Partner & CCO',
                'email' => 'arlton@qavyx.com',
                'order' => 2,
                'is_active' => true,
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Arlton Lowry');

        $memberId = $createResponse->json('data.id');

        // Toggle Active
        $toggleResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/admin/team/{$memberId}/toggle-active");

        $toggleResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('is_active', false);
    }
}
