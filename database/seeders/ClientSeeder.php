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
                'industry_id' => $fintech?->id,
                'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://stripe.com',
                'description' => 'Payment processing infrastructure and billing workflows.',
                'featured' => true,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Revolut Global',
                'industry_id' => $fintech?->id,
                'logo' => 'https://images.unsplash.com/photo-1628157582853-a796fa650a6a?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://revolut.com',
                'description' => 'Next-generation borderless financial super app.',
                'featured' => true,
                'order' => 2,
                'is_active' => true,
            ],

            // AI & SaaS
            [
                'name' => 'Synthetix AI',
                'industry_id' => $aiSaas?->id,
                'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://synthetix.io',
                'description' => 'Autonomous code intelligence and model orchestration.',
                'featured' => true,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Datadog Systems',
                'industry_id' => $aiSaas?->id,
                'logo' => 'https://images.unsplash.com/photo-1614680376593-902f749f7ffc?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://datadoghq.com',
                'description' => 'Real-time observability and cloud monitoring suite.',
                'featured' => true,
                'order' => 4,
                'is_active' => true,
            ],

            // Healthcare
            [
                'name' => 'BioNova Therapeutics',
                'industry_id' => $healthcare?->id,
                'logo' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://bionovamed.com',
                'description' => 'Genomic sequencing and clinical intelligence portals.',
                'featured' => false,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'PulseMed Telehealth',
                'industry_id' => $healthcare?->id,
                'logo' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://pulsemed.health',
                'description' => 'HIPAA-compliant telemedicine consultations and records.',
                'featured' => true,
                'order' => 6,
                'is_active' => true,
            ],

            // E-Commerce
            [
                'name' => 'Nordic Goods Co.',
                'industry_id' => $ecommerce?->id,
                'logo' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://nordicgoods.shop',
                'description' => 'High-conversion headless Shopify storefront and global checkout.',
                'featured' => true,
                'order' => 7,
                'is_active' => true,
            ],

            // Enterprise & Cloud
            [
                'name' => 'CloudScale Networks',
                'industry_id' => $enterprise?->id,
                'logo' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=200&auto=format&fit=crop&q=80',
                'website_url' => 'https://cloudscale.net',
                'description' => 'Kubernetes clustering and multi-region deployment fabrics.',
                'featured' => false,
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($clients as $data) {
            Client::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
