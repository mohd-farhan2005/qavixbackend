<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\LaunchpadLead;
use App\Models\SeoPage;
use App\Models\SeoSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User with user-specified credentials
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Qavyx Admin',
                'email' => 'admin@Qavyx.com',
                'password' => Hash::make('Qavyx@2026'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Global SEO Settings
        SeoSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Qavyx',
                'title_separator' => ' | ',
                'default_meta_title' => 'Qavyx - Cutting-Edge Digital & Web Solutions',
                'default_meta_description' => 'Qavyx delivers high-impact web development, scalable applications, and complete digital growth strategies.',
                'default_meta_keywords' => 'Qavyx, web development, react, laravel, full stack, seo',
                'default_og_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200',
                'twitter_handle' => '@Qavyx',
                'google_site_verification' => 'google-site-verification-token-placeholder',
                'bing_site_verification' => 'bing-site-verification-token-placeholder',
                'sitemap_enabled' => true,
            ]
        );

        // 3. Blog Categories
        $catWeb = Category::updateOrCreate(
            ['slug' => 'web-development'],
            [
                'name' => 'Web Development',
                'description' => 'Insights, tutorials, and best practices in modern full-stack web engineering.',
                'meta_title' => 'Web Development Articles & Guides | Qavyx',
                'meta_description' => 'Explore comprehensive web development articles covering React, Laravel, performance, and APIs.',
            ]
        );

        $catSeo = Category::updateOrCreate(
            ['slug' => 'search-engine-optimization'],
            [
                'name' => 'Search Engine Optimization',
                'description' => 'Actionable SEO tactics, technical audits, meta tagging, and search ranking strategies.',
                'meta_title' => 'Search Engine Optimization (SEO) Guides | Qavyx',
                'meta_description' => 'Master on-page SEO, technical indexing, structured data, and search rankings.',
            ]
        );

        $catCloud = Category::updateOrCreate(
            ['slug' => 'performance-and-cloud'],
            [
                'name' => 'Performance & Cloud',
                'description' => 'Techniques to achieve sub-second load times, Core Web Vitals optimization, and cloud architecture.',
                'meta_title' => 'Performance & Cloud Engineering | Qavyx',
                'meta_description' => 'Learn how to speed up web applications, pass Core Web Vitals, and scale cloud workloads.',
            ]
        );

        // 4. Page SEO Configurations for React Routes
        $pages = [
            [
                'page_name' => 'Home Page',
                'path' => '/',
                'meta_title' => 'Qavyx - Transform Your Digital Presence with Next-Gen Engineering',
                'meta_description' => 'We engineer bespoke web applications using React and Laravel, designed for maximum speed, security, and search visibility.',
                'meta_keywords' => 'software development, react apps, laravel backend, web design, digital agency, Qavyx',
                'canonical_url' => 'http://localhost/Qavyx-back',
                'robots' => 'index, follow',
                'og_title' => 'Qavyx - Next-Gen Web Applications & Digital Products',
                'og_description' => 'Transforming businesses with custom web applications, responsive designs, and top-tier search engine optimization.',
                'og_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200',
                'og_type' => 'website',
                'twitter_card' => 'summary_large_image',
                'schema_markup' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Qavyx',
                    'url' => 'http://localhost/Qavyx-back',
                    'logo' => 'http://localhost/Qavyx-back/logo.png',
                    'sameAs' => [
                        'https://twitter.com/Qavyx',
                        'https://linkedin.com/company/Qavyx',
                    ],
                ],
            ],
            [
                'page_name' => 'About Us',
                'path' => '/about',
                'meta_title' => 'About Qavyx - Innovators in High-Performance Digital Solutions',
                'meta_description' => 'Learn about the mission, team, and technology stack powering Qavyx. We partner with ambitious brands to build scalable web products.',
                'meta_keywords' => 'about Qavyx, digital agency, tech team, react specialists, laravel experts',
                'canonical_url' => 'http://localhost/Qavyx-back/about',
                'robots' => 'index, follow',
                'og_title' => 'About Qavyx - Our Vision, Values & Technology',
                'og_description' => 'Discover how Qavyx helps startups and enterprises scale through engineering excellence.',
                'og_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200',
                'og_type' => 'website',
            ],
            [
                'page_name' => 'Services',
                'path' => '/services',
                'meta_title' => 'Full-Stack Web Development & Technical SEO Services | Qavyx',
                'meta_description' => 'From React frontend engineering to robust Laravel API architecture and technical SEO optimization, see what we offer.',
                'meta_keywords' => 'services, full stack development, technical seo, react development, laravel api, performance optimization',
                'canonical_url' => 'http://localhost/Qavyx-back/services',
                'robots' => 'index, follow',
                'og_title' => 'Custom Software & Technical SEO Services - Qavyx',
                'og_description' => 'High-velocity web development and digital marketing solutions.',
                'og_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'og_type' => 'website',
            ],
            [
                'page_name' => 'Portfolio',
                'path' => '/portfolio',
                'meta_title' => 'Our Work & Case Studies | Qavyx Portfolio',
                'meta_description' => 'Browse our portfolio of successful React and Laravel client projects, ecommerce solutions, and high-ranking platforms.',
                'meta_keywords' => 'portfolio, client work, web design portfolio, case studies, Qavyx projects',
                'canonical_url' => 'http://localhost/Qavyx-back/portfolio',
                'robots' => 'index, follow',
                'og_title' => 'Featured Client Projects & Case Studies - Qavyx',
                'og_description' => 'Explore the transformative web solutions built by Qavyx.',
                'og_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=1200',
                'og_type' => 'website',
            ],
            [
                'page_name' => 'Blog & Insights',
                'path' => '/blogs',
                'meta_title' => 'Engineering & SEO Insights Blog | Qavyx',
                'meta_description' => 'Articles, tutorials, and practical strategies on React, Laravel, headless architecture, and technical SEO rankings.',
                'meta_keywords' => 'tech blog, seo articles, react tutorials, laravel tips, web development insights',
                'canonical_url' => 'http://localhost/Qavyx-back/blogs',
                'robots' => 'index, follow',
                'og_title' => 'Qavyx Insights - Web Engineering & Growth Blog',
                'og_description' => 'Explore our latest thinking on modern web architecture and SEO.',
                'og_image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200',
                'og_type' => 'website',
            ],
            [
                'page_name' => 'Contact Us',
                'path' => '/contact',
                'meta_title' => 'Get in Touch with Qavyx - Start Your Next Web Project',
                'meta_description' => 'Ready to build your next web app or accelerate your SEO? Reach out to our engineering team today for a free consultation.',
                'meta_keywords' => 'contact Qavyx, hire react developers, hire laravel developers, web project quote',
                'canonical_url' => 'http://localhost/Qavyx-back/contact',
                'robots' => 'index, follow',
                'og_title' => 'Contact Qavyx - Let us Build Something Great Together',
                'og_description' => 'Get in touch with our team for consultations, custom software development, and SEO audits.',
                'og_image' => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1200',
                'og_type' => 'website',
            ],
        ];

        foreach ($pages as $p) {
            SeoPage::updateOrCreate(['path' => $p['path']], $p);
        }

        // 5. Seed High-Quality Sample Blog Posts with Full SEO Metadata
        Blog::updateOrCreate(
            ['slug' => 'mastering-seo-in-modern-react-applications'],
            [
                'title' => 'Mastering SEO in Modern React & Single Page Applications',
                'excerpt' => 'Single Page Applications (SPAs) often face indexing hurdles. Discover how to conquer dynamic meta tags, SSR/SSG, and Googlebot hydration.',
                'content' => '<h2>Why SEO Matters for React Applications</h2><p>React is renowned for delivering blazingly fast client-side experiences. However, search engines need to crawl HTML, read meta descriptions, and index content accurately. Without proper SEO mechanisms, your React application risks remaining invisible to search engines.</p><h3>Key Strategies for React SEO</h3><ul><li><strong>Dynamic Meta Tag Management:</strong> Utilizing react-helmet-async to inject dynamic meta titles, descriptions, and OpenGraph tags per route.</li><li><strong>Prerendering and Server-Side Rendering (SSR):</strong> Serving pre-rendered HTML to search engine bots while delivering interactive React SPAs to users.</li><li><strong>Structured Data (JSON-LD):</strong> Feeding schema markup directly to Googlebot to earn rich snippet cards on SERPs.</li></ul><p>By connecting your React frontend to a Laravel SEO API, your marketing and content team can manage titles, descriptions, and social tags without touching frontend code.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=1200',
                'featured_image_alt' => 'Search engine ranking and digital marketing dashboard',
                'featured_image_caption' => 'Modern technical SEO strategies for dynamic web apps',
                'category_id' => $catSeo->id,
                'author_id' => $admin->id,
                'author_name' => 'Qavyx Admin',
                'tags' => ['React', 'SEO', 'Meta Tags', 'Googlebot'],
                'status' => 'published',
                'is_featured' => true,
                'views_count' => 142,
                'reading_time' => 4,
                'published_at' => now()->subDays(3),
                'meta_title' => 'How to Master React SEO in 2026: The Complete Technical Guide',
                'meta_description' => 'Learn how to optimize React single-page applications for search engines with dynamic meta tags, social OpenGraph cards, and JSON-LD schema.',
                'meta_keywords' => 'react seo, single page application seo, react-helmet, meta tags, googlebot indexing',
                'canonical_url' => 'http://localhost/Qavyx-back/blog/mastering-seo-in-modern-react-applications',
                'robots_index' => true,
                'robots_follow' => true,
                'og_title' => 'How to Master React SEO in 2026: Complete Guide',
                'og_description' => 'Actionable strategies to get your React web app ranking #1 on Google with dynamic meta tags and structured data.',
                'og_image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=1200',
                'og_type' => 'article',
                'twitter_card' => 'summary_large_image',
                'twitter_title' => 'Mastering React SEO & Meta Tags',
                'twitter_description' => 'A practical guide to conquering search engine indexing with React and Laravel API backends.',
                'twitter_image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=1200',
                'schema_markup' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'BlogPosting',
                    'headline' => 'Mastering SEO in Modern React & Single Page Applications',
                    'description' => 'Learn how to optimize React single-page applications for search engines with dynamic meta tags, social OpenGraph cards, and JSON-LD schema.',
                    'image' => ['https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=1200'],
                    'author' => [
                        '@type' => 'Person',
                        'name' => 'Qavyx Admin',
                    ],
                ],
            ]
        );

        Blog::updateOrCreate(
            ['slug' => 'why-laravel-and-react-is-the-ideal-full-stack-pairing'],
            [
                'title' => 'Why Laravel & React is the Ideal Architecture for High-Growth Apps',
                'excerpt' => 'Combining Laravel’s rock-solid backend capabilities with React’s reactive user interface creates an unbeatable software stack.',
                'content' => '<h2>The Power of Decoupled Architecture</h2><p>Modern applications demand separation of concerns. By choosing Laravel as an API powerhouse and React as an interactive frontend, engineering teams gain immense development velocity and security.</p><h3>Why Laravel Excels as an API Engine</h3><p>Laravel provides built-in Sanctum token authentication, robust Eloquent ORM relations, schema migrations, automated rate limiting, and seamless CORS middleware out of the box.</p><h3>Why React Complements It Perfectly</h3><p>React enables fluid user experiences, instant UI feedback, client-side caching, and rapid component reuse.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200',
                'featured_image_alt' => 'Modern code on computer monitor',
                'featured_image_caption' => 'Clean API architecture with Laravel and React',
                'category_id' => $catWeb->id,
                'author_id' => $admin->id,
                'author_name' => 'Qavyx Admin',
                'tags' => ['Laravel', 'React', 'Full Stack', 'REST API'],
                'status' => 'published',
                'is_featured' => true,
                'views_count' => 98,
                'reading_time' => 3,
                'published_at' => now()->subDays(7),
                'meta_title' => 'Why Laravel + React is the Best Stack for Web Apps in 2026',
                'meta_description' => 'Discover why combining a Laravel REST API backend with a React SPA frontend delivers exceptional scalability, speed, and developer productivity.',
                'meta_keywords' => 'laravel react, full stack architecture, laravel api, react spa, web development stack',
                'robots_index' => true,
                'robots_follow' => true,
                'og_title' => 'Why Laravel + React is the Best Stack for Modern Web Apps',
                'og_description' => 'Unlocking the power of Laravel API backends with React client applications.',
                'og_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200',
            ]
        );

        Blog::updateOrCreate(
            ['slug' => 'optimizing-core-web-vitals-for-peak-search-rankings'],
            [
                'title' => 'Optimizing Core Web Vitals for Peak Search Rankings',
                'excerpt' => 'Google evaluates real-world user experience through Core Web Vitals (LCP, INP, and CLS). Here is how to achieve straight green scores.',
                'content' => '<h2>Understanding the Core Web Vitals</h2><p>Google directly correlates user experience metrics with search rank positions. The 3 primary metrics to monitor are:</p><ul><li><strong>Largest Contentful Paint (LCP):</strong> Measures perceived loading speed. Target: under 2.5s.</li><li><strong>Interaction to Next Paint (INP):</strong> Replaced FID to measure interface responsiveness. Target: under 200ms.</li><li><strong>Cumulative Layout Shift (CLS):</strong> Measures visual stability. Target: score below 0.1.</li></ul><p>By implementing lazy-loaded media, pre-allocating image dimension ratios, and utilizing fast API responses, you can ensure your web app satisfies every Core Web Vital benchmark.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200',
                'featured_image_alt' => 'Analytics performance chart',
                'featured_image_caption' => 'Core Web Vitals analytics metrics',
                'category_id' => $catCloud->id,
                'author_id' => $admin->id,
                'author_name' => 'Qavyx Admin',
                'tags' => ['Core Web Vitals', 'Performance', 'LCP', 'INP', 'SEO'],
                'status' => 'published',
                'is_featured' => false,
                'views_count' => 76,
                'reading_time' => 4,
                'published_at' => now()->subDays(12),
                'meta_title' => 'Core Web Vitals Guide 2026: Boost Rankings with Fast Load Times',
                'meta_description' => 'A practical guide to passing Google Core Web Vitals (LCP, INP, CLS) and lifting your website search rankings.',
                'meta_keywords' => 'core web vitals, page speed, google ranking factors, lcp, inp, cls',
                'robots_index' => true,
                'robots_follow' => true,
                'og_title' => 'How to Ace Google Core Web Vitals in 2026',
                'og_description' => 'A developer roadmap to lightning-fast load times and top Google rankings.',
                'og_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200',
            ]
        );

        // 6. Frequently Asked Questions (FAQs)
        $faqs = [
            [
                'question' => 'How does the React frontend integrate with the Laravel backend?',
                'category' => 'Web Development',
                'answer' => 'The React frontend communicates with Laravel via high-performance RESTful APIs authenticated with Laravel Sanctum tokens or session cookies. React manages client rendering and interactivity, while Laravel provides the database schema, business logic, media storage, and SEO meta services.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Can I manage page SEO meta tags and descriptions from the admin panel?',
                'category' => 'SEO & Marketing',
                'answer' => 'Yes. The Qavyx Admin Panel includes full page-level SEO controls allowing you to configure custom meta titles, descriptions, OpenGraph social preview images, Twitter cards, and Google Schema.org structured data for every React route without touching code.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How are FAQs converted into Google FAQPage rich search snippets?',
                'category' => 'SEO & Marketing',
                'answer' => 'The backend automatically formats all published FAQs into Google-compliant FAQPage JSON-LD schema markup available at the /api/faqs endpoint. Your React frontend simply renders this schema inside a script tag or React Helmet to earn rich expandable FAQ snippets on Google search results.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What rich text capabilities are available in the Blog creator?',
                'category' => 'Content Management',
                'answer' => 'The blog editor is powered by TinyMCE with complete formatting tools including headings, bold/italics, quotes, code blocks, tables, hyperlinking, bullet and numbered lists, and word counters matching professional digital marketing standards.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Where can I upload and manage images or media assets?',
                'category' => 'Media & Assets',
                'answer' => 'Use the built-in Media Library in the admin panel to upload images, banners, and documents. The system optimizes the files, generates public URLs, and provides 1-click clipboard copy buttons for effortless insertion into blog posts.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'How do I handle contact form inquiries submitted from the website?',
                'category' => 'General',
                'answer' => 'Visitors can submit lead inquiries through the public POST /api/contact endpoint. Submissions are instantly stored in the database and displayed in the Customer Inquiries inbox in your admin panel with unread badges and status filters.',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::updateOrCreate(
                ['question' => $faqData['question']],
                $faqData
            );
        }

        // 7. Seed Sample Launchpad Applicants
        $launchpadFounders = [
            [
                'full_name' => 'Aarav Sharma',
                'email' => 'aarav@neuroflow.ai',
                'whatsapp_number' => '+91 98765 43210',
                'startup_name' => 'NeuroFlow AI',
                'industry' => 'Artificial Intelligence & SaaS',
                'status' => 'new',
                'is_read' => false,
                'notes' => 'Applied for AI agent development & cloud scaling architecture.',
            ],
            [
                'full_name' => 'Sneha Patel',
                'email' => 'sneha@fintechpulse.io',
                'whatsapp_number' => '+91 91234 56789',
                'startup_name' => 'FinPulse',
                'industry' => 'Fintech',
                'status' => 'contacted',
                'is_read' => true,
                'notes' => 'Connected on WhatsApp. Scheduled discovery call for next Tuesday.',
            ],
            [
                'full_name' => 'Rohan Mehta',
                'email' => 'rohan@greenvolt.co',
                'whatsapp_number' => '+91 99887 76655',
                'startup_name' => 'GreenVolt EV',
                'industry' => 'CleanTech / IoT',
                'status' => 'qualified',
                'is_read' => true,
                'notes' => 'Qualified for the first cohort. Pitch deck reviewed.',
            ],
        ];

        foreach ($launchpadFounders as $founder) {
            LaunchpadLead::updateOrCreate(
                ['email' => $founder['email']],
                $founder
            );
        }
    }
}
