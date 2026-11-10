<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Industry;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fintech = Industry::where('slug', 'fintech-web3')->first();
        $aiSaas = Industry::where('slug', 'ai-saas')->first();
        $healthcare = Industry::where('slug', 'healthcare-medtech')->first();
        $ecommerce = Industry::where('slug', 'ecommerce-retail')->first();
        $enterprise = Industry::where('slug', 'enterprise-cloud')->first();

        $clients = [
            // Fintech
            [
                'name' => 'Stripe Payments',
                'slug' => 'stripe-payments',
                'industry_id' => $fintech?->id,
                'tagline' => 'Global financial infrastructure and high-throughput checkout architecture',
                'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://stripe.com',
                'location' => 'San Francisco, CA',
                'project_year' => '2026',
                'client_type' => 'Enterprise Architecture',
                'description' => 'Payment processing infrastructure and billing workflows for modern commerce.',
                'about' => 'Engineered a resilient microservices bridge and custom telemetry pipeline to optimize global multi-currency checkout routing, reducing transaction failures by 34%.',
                'challenge' => 'Legacy payment tunnels suffered from regional latency spikes and cart abandonment on emerging market credit rails.',
                'solution' => 'Designed an edge-accelerated routing gateway that intelligently proxies tokenized payments to optimal card networks in sub-120ms.',
                'services' => ['Payment Gateway Architecture', 'Microservices Optimization', 'React Edge UI', 'Cloud DevOps'],
                'stat_1_value' => '+34%',
                'stat_1_label' => 'Checkout Conversion Lift',
                'stat_2_value' => ' sub-120ms',
                'stat_2_label' => 'Global API Latency',
                'stat_3_value' => '$800M+',
                'stat_3_label' => 'Annual Volume Routed',
                'testimonial_quote' => 'Qavyx engineered an exceptionally resilient infrastructure that handled our Black Friday spikes without breaking a sweat.',
                'testimonial_author' => 'Marcus Vance',
                'testimonial_position' => 'Head of Core Infrastructure',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200',
                'meta_title' => 'Stripe Payments Case Study | Qavyx High-Scale Engineering',
                'meta_description' => 'How Qavyx optimized cross-border transaction latency and checkout conversion rates for leading fintech enterprise.',
                'featured' => true,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Revolut Global',
                'slug' => 'revolut-global',
                'industry_id' => $fintech?->id,
                'tagline' => 'Borderless retail and wealth management mobile experience',
                'logo' => 'https://images.unsplash.com/photo-1628157582853-a796fa650a6a?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://revolut.com',
                'location' => 'London, UK',
                'project_year' => '2025',
                'client_type' => 'Mobile & Web SuperApp',
                'description' => 'Next-generation borderless financial super app and treasury dashboard.',
                'about' => 'Reimagined the B2B treasury management dashboard with instant multi-currency FX conversions and automated corporate card issuance.',
                'challenge' => 'Corporate finance teams required seamless multi-entity reconciliation across 28 global jurisdictions.',
                'solution' => 'Implemented real-time WebSocket ledger streams and automated accounting synchronizations for Xero, QuickBooks, and NetSuite.',
                'services' => ['Fintech UI/UX Design', 'Fullstack Web App', 'Realtime WebSockets', 'Automated Testing'],
                'stat_1_value' => '4.9★',
                'stat_1_label' => 'App Store Rating',
                'stat_2_value' => '3.2x',
                'stat_2_label' => 'Daily Active Usage',
                'stat_3_value' => '99.99%',
                'stat_3_label' => 'Core Uptime',
                'testimonial_quote' => 'The precision and aesthetic polish Qavyx delivered was beyond our highest expectations.',
                'testimonial_author' => 'Helena Croft',
                'testimonial_position' => 'Product Lead, Corporate Rails',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200',
                'meta_title' => 'Revolut Global Treasury Case Study | Qavyx',
                'meta_description' => 'Discover how Qavyx helped modernize Revoluts B2B treasury experience.',
                'featured' => true,
                'order' => 2,
                'is_active' => true,
            ],

            // AI & SaaS
            [
                'name' => 'Synthetix AI',
                'slug' => 'synthetix-ai',
                'industry_id' => $aiSaas?->id,
                'tagline' => 'Autonomous code synthesis and workflow agent orchestration',
                'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://synthetix.io',
                'location' => 'Seattle, WA',
                'project_year' => '2026',
                'client_type' => 'GenAI Platform',
                'description' => 'Autonomous code intelligence and model orchestration platform for developer teams.',
                'about' => 'Constructed a distributed prompt evaluation harness and streaming generative UI components for real-time model interaction.',
                'challenge' => 'High latency during complex multi-step reasoning caused developer drop-off during live coding sessions.',
                'solution' => 'Built an optimistic streaming renderer with AST parsing on WebAssembly, slashing perceived wait times to near zero.',
                'services' => ['Generative UI', 'LLM Agent Pipelines', 'Next.js Frontend', 'WASM Acceleration'],
                'stat_1_value' => '480%',
                'stat_1_label' => 'Developer Adoption',
                'stat_2_value' => '$14M',
                'stat_2_label' => 'Series A Capital',
                'stat_3_value' => '< 50ms',
                'stat_3_label' => 'Token First-Byte Time',
                'testimonial_quote' => 'They took our raw AI models and turned them into a consumer-grade developer experience that won over Silicon Valley.',
                'testimonial_author' => 'Dr. Andrew Zhao',
                'testimonial_position' => 'Co-Founder & Chief Scientist',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200',
                'meta_title' => 'Synthetix AI Platform Case Study | Qavyx',
                'meta_description' => 'Scaling generative code synthesis with lightning-fast streaming UI and WebAssembly.',
                'featured' => true,
                'order' => 3,
                'is_active' => true,
            ],

            // Healthcare
            [
                'name' => 'BioNova Therapeutics',
                'slug' => 'bionova-therapeutics',
                'industry_id' => $healthcare?->id,
                'tagline' => 'Precision clinical genomics and computational oncology intelligence',
                'logo' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://bionovamed.com',
                'location' => 'Boston, MA',
                'project_year' => '2025',
                'client_type' => 'Clinical Biotech',
                'description' => 'Genomic sequencing and clinical intelligence portals for oncologists.',
                'about' => 'Built an interactive 3D biomolecular viewer and automated genomic variant annotation pipeline compliant with HIPAA and GDPR.',
                'challenge' => 'Massive multi-gigabyte genomic variant callsets were taking 40+ minutes to load and parse in clinical browsers.',
                'solution' => 'Architected a WebGL and chunked Parquet data pipeline that renders patient variant maps instantaneously.',
                'services' => ['3D Molecular Visualizations', 'HIPAA Compliance', 'Genomic Data Pipelines'],
                'stat_1_value' => '94%',
                'stat_1_label' => 'Faster Diagnostic Analysis',
                'stat_2_value' => '100%',
                'stat_2_label' => 'HIPAA Compliant',
                'stat_3_value' => '120k+',
                'stat_3_label' => 'Patient Profiles Protected',
                'testimonial_quote' => 'Qavyx made complex genomic data intuitive and accessible to clinicians worldwide.',
                'testimonial_author' => 'Dr. Rebecca Stern',
                'testimonial_position' => 'Chief Medical Officer',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1594824813589-4a9f9d78e7ff?w=200',
                'meta_title' => 'BioNova Therapeutics Case Study | Qavyx Biotech Portal',
                'meta_description' => 'HIPAA-compliant genomic sequencing portal engineered for hospital oncology teams.',
                'featured' => false,
                'order' => 4,
                'is_active' => true,
            ],

            // E-Commerce
            [
                'name' => 'Nordic Goods Co.',
                'slug' => 'nordic-goods-co',
                'industry_id' => $ecommerce?->id,
                'tagline' => 'Minimalist Scandinavian home architecture & sustainable e-commerce',
                'logo' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://nordicgoods.shop',
                'location' => 'Stockholm, Sweden',
                'project_year' => '2026',
                'client_type' => 'Headless DTC E-Commerce',
                'description' => 'High-conversion headless Shopify storefront and global localized checkout.',
                'about' => 'Created an ultra-fast headless React storefront with sub-second page transitions, dynamic currency routing, and 3D augmented product previews.',
                'challenge' => 'Standard template storefronts were sluggish and failed to convey the premium luxury aesthetic of the brand.',
                'solution' => 'Engineered an edge-cached Next.js frontend with Tailwind and GSAP animations, achieving a 99/100 Google Lighthouse score.',
                'services' => ['Headless Commerce', 'Shopify Storefront API', 'GSAP Micro-Interactions', 'Global CDN'],
                'stat_1_value' => '+62%',
                'stat_1_label' => 'Average Order Value',
                'stat_2_value' => '99/100',
                'stat_2_label' => 'Google Performance Score',
                'stat_3_value' => '1.2s',
                'stat_3_label' => 'Average Load Time',
                'testimonial_quote' => 'Our online sales doubled in the first quarter post-launch. The speed and aesthetic is unmatched.',
                'testimonial_author' => 'Lukas Lindqvist',
                'testimonial_position' => 'Founder & Brand Director',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200',
                'meta_title' => 'Nordic Goods Co. E-Commerce Case Study | Qavyx',
                'meta_description' => 'How Qavyx built a sub-second headless e-commerce store that increased order value by 62%.',
                'featured' => true,
                'order' => 5,
                'is_active' => true,
            ],

            // Enterprise Cloud
            [
                'name' => 'TerraCloud Global',
                'slug' => 'terracloud-global',
                'industry_id' => $enterprise?->id,
                'tagline' => 'Multi-cloud Kubernetes fleet management and infrastructure as code',
                'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1920&auto=format&fit=crop&q=80',
                'website_url' => 'https://terracloud.network',
                'location' => 'Austin, TX',
                'project_year' => '2026',
                'client_type' => 'Enterprise Infrastructure',
                'description' => 'Multi-cloud orchestration and observability portal for Fortune 500 engineering leaders.',
                'about' => 'Delivered an enterprise security portal allowing DevOps engineers to audit container vulnerabilities and policy compliance across AWS, GCP, and Azure.',
                'challenge' => 'Fragmented cloud inventories left enterprise compliance teams blind to shadow cloud instances.',
                'solution' => 'Built an automated discovery and RBAC policy engine with instant audit reporting and Slack alert webhooks.',
                'services' => ['Enterprise RBAC', 'Cloud Security Engineering', 'Kubernetes Fleet API', 'Audit Dashboards'],
                'stat_1_value' => '10x',
                'stat_1_label' => 'Audit Speedup',
                'stat_2_value' => 'Zero',
                'stat_2_label' => 'Security Vulnerabilities',
                'stat_3_value' => '50k+',
                'stat_3_label' => 'Nodes Monitored',
                'testimonial_quote' => 'They gave us total visibility across tens of thousands of container nodes with a gorgeous interface.',
                'testimonial_author' => 'Jonathan Vance',
                'testimonial_position' => 'VP of Cloud Security',
                'testimonial_avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=200',
                'meta_title' => 'TerraCloud Multi-Cloud Security Case Study | Qavyx',
                'meta_description' => 'Auditing tens of thousands of enterprise Kubernetes nodes with Qavyx cloud engineering.',
                'featured' => true,
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::updateOrCreate(
                ['slug' => $clientData['slug']],
                $clientData
            );
        }
    }
}
