<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            [
                'name' => 'Fintech & Web3',
                'slug' => 'fintech-web3',
                'description' => 'Digital banking, decentralized finance, high-frequency trading & payment infrastructure.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'AI & SaaS Platforms',
                'slug' => 'ai-saas',
                'description' => 'Enterprise artificial intelligence, LLM orchestration & cloud productivity software.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Healthcare & MedTech',
                'slug' => 'healthcare-medtech',
                'description' => 'Telehealth ecosystems, clinical diagnostic tools & patient management portals.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'E-Commerce & Retail',
                'slug' => 'ecommerce-retail',
                'description' => 'Direct-to-consumer digital storefronts, marketplace integrations & global logistics.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise & Cloud Infrastructure',
                'slug' => 'enterprise-cloud',
                'description' => 'DevOps automation, microservices architecture & cybersecurity compliance.',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($industries as $data) {
            Industry::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}

