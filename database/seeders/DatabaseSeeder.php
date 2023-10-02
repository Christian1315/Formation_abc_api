<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ##======== CREATION D'UN ADMIN PAR DEFAUT ============####
        $userData = [
            'lastname' => 'Christian',
            'firstname' => 'Christian',
            'lastname' => 'GOGO',
            'email' => 'gogochristian009@gmail.com',
            'password' => '$2y$10$8JhR1nysW.mE1hI7CqkArelFuSLglJaBxJK5w1yLaNSpedc.4q.fq', #gogo@1315
            'phone' => "22961765590",
        ];

        \App\Models\User::factory()->create($userData);
    }
}
