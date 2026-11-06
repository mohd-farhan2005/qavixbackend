<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\SeoPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeoAndBlogApiTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure admin user exists with requested credentials
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

    public function test_admin_can_login_with_provided_credentials(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'username' => 'admin',
            'password' => 'qavix@2026',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'username', 'email'],
            ]);
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_public_can_fetch_published_blogs(): void
    {
        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'total'],
            ]);
    }

    public function test_public_can_fetch_page_seo_metadata(): void
    {
        $response = $this->getJson('/api/seo?path=/about');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'description',
                    'openGraph' => ['title', 'description'],
                    'twitter' => ['title'],
                ],
            ]);
    }

    public function test_admin_can_create_blog_post_with_seo_metadata(): void
    {
        $admin = User::where('username', 'admin')->first();
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/blogs', [
                'title' => 'Test Blog Post with Meta Tags',
                'slug' => 'test-blog-post-with-meta-tags',
                'content' => '<p>This is a test blog post content.</p>',
                'status' => 'published',
                'meta_title' => 'Custom Meta Title for SEO',
                'meta_description' => 'Custom meta description for search engines.',
                'meta_keywords' => 'testing, laravel, seo',
                'og_title' => 'Custom OG Title',
                'og_description' => 'Custom OG Description',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Test Blog Post with Meta Tags')
            ->assertJsonPath('data.meta_title', 'Custom Meta Title for SEO');

        // Verify public API returns the post and its SEO bundle
        $publicResponse = $this->getJson('/api/blogs/test-blog-post-with-meta-tags');
        $publicResponse->assertStatus(200)
            ->assertJsonPath('data.title', 'Test Blog Post with Meta Tags')
            ->assertJsonPath('seo.title', 'Custom Meta Title for SEO')
            ->assertJsonPath('seo.description', 'Custom meta description for search engines.');
    }

    public function test_admin_can_manage_page_seo(): void
    {
        $admin = User::where('username', 'admin')->first();
        $token = $admin->createToken('test-token')->plainTextToken;

        // Create page SEO
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/seo-pages', [
                'page_name' => 'Pricing Page',
                'path' => '/pricing',
                'meta_title' => 'Affordable Web Development Packages | Qavix Pricing',
                'meta_description' => 'Explore transparent pricing packages for web apps and SEO.',
                'meta_keywords' => 'pricing, web development rates',
                'robots' => 'index, follow',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        // Fetch via public SEO resolver
        $publicResponse = $this->getJson('/api/seo?path=/pricing');
        $publicResponse->assertStatus(200)
            ->assertJsonPath('data.page_name', 'Pricing Page');
    }

    public function test_sitemap_endpoints(): void
    {
        $xmlResponse = $this->get('/api/sitemap.xml');
        $xmlResponse->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $jsonResponse = $this->getJson('/api/sitemap');
        $jsonResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['pages', 'blogs']);
    }

    public function test_admin_can_manage_categories(): void
    {
        $admin = User::where('username', 'admin')->first();
        $token = $admin->createToken('test-token')->plainTextToken;

        // Create Category
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/categories', [
                'name' => 'Artificial Intelligence',
                'description' => 'AI news and insights',
                'meta_title' => 'AI Articles | Qavix',
                'meta_description' => 'Explore the cutting edge of AI.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'artificial-intelligence');

        // Fetch categories via public API
        $publicRes = $this->getJson('/api/categories');
        $publicRes->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_contact_form_submission_and_admin_view(): void
    {
        // Public user submits contact inquiry
        $submitRes = $this->postJson('/api/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+1234567890',
            'subject' => 'Project Inquiry',
            'message' => 'We would like to hire your team for a React and Laravel web application.',
        ]);

        $submitRes->assertStatus(201)
            ->assertJsonPath('success', true);

        // Admin views inquiries
        $admin = User::where('username', 'admin')->first();
        $token = $admin->createToken('test-token')->plainTextToken;

        $adminRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/contacts');

        $adminRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.total', 1);
    }
}
