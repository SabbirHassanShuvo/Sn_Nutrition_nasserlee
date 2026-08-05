<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\PartnerProfile;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah1@example.com',
                'role' => 'Registered Dietitian, RDN',
                'avatarUrl' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'quote' => 'I was already recommending these products to clients — now I earn $2-3k extra per month for doing what I love. The dashboard makes everything transparent.',
                'rating' => 5,
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah2@example.com',
                'role' => 'Registered Dietitian, RDN',
                'avatarUrl' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'quote' => 'I was already recommending these products to clients — now I earn $2-3k extra per month for doing what I love. The dashboard makes everything transparent.',
                'rating' => 3,
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah3@example.com',
                'role' => 'Registered Dietitian, RDN',
                'avatarUrl' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'quote' => 'I was already recommending these products to clients — now I earn $2-3k extra per month for doing what I love. The dashboard makes everything transparent.',
                'rating' => 4,
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah4@example.com',
                'role' => 'Registered Dietitian, RDN',
                'avatarUrl' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=761&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'quote' => 'I was already recommending these products to clients — now I earn $2-3k extra per month for doing what I love. The dashboard makes everything transparent.',
                'rating' => 5,
            ]
        ];

        foreach ($testimonials as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
            ]);

            PartnerProfile::create([
                'user_id' => $user->id,
                'professional_role' => $data['role'],
                'avatar' => $data['avatarUrl'],
            ]);

            Review::create([
                'user_id' => $user->id,
                'quote' => $data['quote'],
                'rating' => $data['rating'],
            ]);
        }
    }
}
