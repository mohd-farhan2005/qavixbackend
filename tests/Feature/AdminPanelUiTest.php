<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\SeoPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Qavix Admin',
                'email' => 'admin@qavix.com',
                'password' => Hash::make('qavix@2026'),
                'role' => 'admin',
            ]
        );
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(302)
            ->assertRedirect('/admin/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200)
            ->assertSee('Admin Control Center')
            ->assertSee('Username or Email')
            ->assertSee('admin / qavix@2026');
    }

    public function test_admin_can_login_via_web_form(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'qavix@2026',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200)
            ->assertSee('System Dashboard')
            ->assertSee('Blog Articles')
            ->assertSee('SEO Engine');
    }

    public function test_admin_can_view_blogs_and_create_page(): void
    {
        $admin = User::where('username', 'admin')->first();

        $listResponse = $this->actingAs($admin)->get('/admin/blogs');
        $listResponse->assertStatus(200)
            ->assertSee('Blog & Article Management', false);

        $createResponse = $this->actingAs($admin)->get('/admin/blogs/create');
        $createResponse->assertStatus(200)
            ->assertSee('SEO Metadata & Search Tags', false)
            ->assertSee('Google Search Engine Snippet Preview', false)
            ->assertSee('SEO Meta Title', false);
    }

    public function test_admin_can_create_blog_with_seo_tags(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/blogs', [
            'title' => 'Web App SEO Guide for React and Laravel',
            'slug' => 'web-app-seo-guide-for-react-and-laravel',
            'excerpt' => 'A comprehensive overview of SEO best practices.',
            'content' => '<p>Here is full content about React and Laravel SEO.</p>',
            'status' => 'published',
            'meta_title' => 'The Best SEO Guide for React Apps 2026',
            'meta_description' => 'Learn how to rank your React app with Laravel APIs.',
            'meta_keywords' => 'react, seo, laravel, web app',
            'robots_index' => '1',
            'robots_follow' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/admin/blogs');

        $this->assertDatabaseHas('blogs', [
            'slug' => 'web-app-seo-guide-for-react-and-laravel',
            'meta_title' => 'The Best SEO Guide for React Apps 2026',
        ]);
    }

    public function test_admin_can_view_and_manage_page_seo(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/seo-pages');
        $response->assertStatus(200)
            ->assertSee('Page Meta Tags & OpenGraph Manager', false);

        $createResponse = $this->actingAs($admin)->get('/admin/seo-pages/create');
        $createResponse->assertStatus(200)
            ->assertSee('Target React Page / Route', false)
            ->assertSee('Google Search Snippet Preview', false);
    }

    public function test_admin_can_view_global_seo_settings(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/seo-settings');
        $response->assertStatus(200)
            ->assertSee('Global SEO Defaults & Webmaster Tools', false)
            ->assertSee('Google Search Console Verification Token', false);
    }
}
