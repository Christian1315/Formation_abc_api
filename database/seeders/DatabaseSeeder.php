<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'firstname' => 'Christian',
            'lastname' => 'GOGO',
            'email' => 'gogochristian009@gmail.com',
            'password' => '$2y$10$8JhR1nysW.mE1hI7CqkArelFuSLglJaBxJK5w1yLaNSpedc.4q.fq', #gogo@1315
            'phone' => "22961765590",
            'compte_actif' => true,
        ];

        $user = \App\Models\User::factory()->create($userData);

        ##======== CREATION DES ROLES PAR DEFAUT ============####

        \App\Models\Role::factory()->create([
            'label' => 'is_transporter'
        ]);

        \App\Models\Role::factory()->create([
            'label' => 'is_sender'
        ]);
        $role_admin = \App\Models\Role::factory()->create([
            'label' => 'is_admin'
        ]);
        \App\Models\Role::factory()->create([
            'label' => 'is_supervisor'
        ]);
        \App\Models\Role::factory()->create([
            'label' => 'is_shipper'
        ]);
        \App\Models\Role::factory()->create([
            'label' => 'is_biller'
        ]);

        ###========= AFFECTATION DU ROLE **is_admin** AU USER ADMIN =========### 
        $user->roles()->attach($role_admin);


        ##======== CREATION DES TYPES DE MOYEN DE TRANSPORT PAR DEFAUT ============####

        $transportTypes = [
            [
                "name" => "Camion",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Car",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Train",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
        ];

        foreach ($transportTypes as $transportType) {
            \App\Models\Type::factory()->create($transportType);
        }


        #=========== CREER DES STATUS D'UN TRANSPORT PAR DEFAUT ============#

        $transport_status = [
            [
                "name" => "En cour",
                "description" => "Ce moyen de transport est en cour de traitement!",
            ],
            [
                "name" => "Validé",
                "description" => "Ce moyen de transport est validé",
            ],
        ];

        foreach ($transport_status as $transport_statu) {
            \App\Models\TransportStatus::factory()->create($transport_statu);
        }

        #=========== CREER DES STATUS D'UN EXPEDITEUR PAR DEFAUT ============#

        $fret_status = [
            [
                "name" => "En cours",
                "description" => "Ce Fret est en cour de traitement!",
            ],
            [
                "name" => "Programmé",
                "description" => "Ce Fret est programmé!",
            ],
            [
                "name" => "Terminé",
                "description" => "Ce Fret est en attente de traitement!",
            ],
            [
                "name" => "Publié",
                "description" => "Ce Fret est validé",
            ],
        ];

        foreach ($fret_status as $fret_statu) {
            \App\Models\FretStatus::factory()->create($fret_statu);
        }


        #=========== CREER DES TYPES DE FRET PAR DEFAUT ============#

        $fret_types = [
            [
                "name" => "Alimentaire",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Produit agricole",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Matériel de Construction",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Divers",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
        ];

        foreach ($fret_types as $fret_type) {
            \App\Models\FretType::factory()->create($fret_type);
        }
    }
}
