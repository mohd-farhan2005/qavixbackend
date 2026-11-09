<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Zack Hill',
                'position' => 'Founder & CEO',
                'email' => 'zack@qavyx.com',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=85',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Arlton Lowry',
                'position' => 'Partner & CCO',
                'email' => 'arlton@qavyx.com',
                'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=85',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Devine',
                'position' => 'Director of Projects',
                'email' => 'sarah@qavyx.com',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=85',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Will Carter',
                'position' => 'Director of Accounts',
                'email' => 'will@qavyx.com',
                'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=85',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Calvin Bramlett',
                'position' => 'Creative & Design Director',
                'email' => 'calvin@qavyx.com',
                'photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=85',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($members as $data) {
            TeamMember::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
