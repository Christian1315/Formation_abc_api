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
            'firstname' => 'Christian',
            'lastname' => 'GOGO',
            'email' => 'gogochristian009@gmail.com',
            'password' => '$2y$10$8JhR1nysW.mE1hI7CqkArelFuSLglJaBxJK5w1yLaNSpedc.4q.fq', #gogo@1315
            'phone' => "22961765590",
            'compte_actif' => true,
        ];

        $user = \App\Models\User::factory()->create($userData);

        ##======== CREATION DES ROLES PAR DEFAUT ============####

        $roles = [
            [
                'label' => 'is_transporter'
            ],
            [
                'label' => 'is_sender'
            ],
            [
                'label' => 'is_admin'
            ],
            [
                'label' => 'is_supervisor'
            ],
            [
                'label' => 'is_shipper'
            ],
            [
                'label' => 'is_biller'
            ]
        ];

        foreach ($roles as $role) {
            \App\Models\Role::factory()->create($role);
        };

        ###========= AFFECTATION DU ROLE **is_admin** AU USER ADMIN =========### 
        $role_admin = User::find(1);
        $user->roles()->attach($role_admin);


        ##======== CREATION DES TYPES DE MOYEN DE TRANSPORT PAR DEFAUT ============####

        $transportTypes = [
            [
                "name" => "P11 (Porteur avec 1 essieu devant et 1 essieu arrière)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "P12 (Porteur avec 1 essieu devant et 2 essieux derrière)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "T11S2	(Tracteur avec 1 essieu devant, 1 essieu derrière et semi-remorque avec 2 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "T11S3	(Tracteur avec 1 essieu devant, 1 essieu derrière et semi-remorque avec 3 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],

            [
                "name" => "T11S3	(Tracteur avec 1 essieu devant, 1 essieu derrière et semi-remorque avec 3 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "T12S2	(Tracteur avec 1 essieu devant, 2 essieux derrière et semi-remorque avec 2 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "T12S3	(Tracteur avec 1 essieu devant, 2 essieux derrière et semi-remorque avec 3 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "T12S4	(Tracteur avec 1 essieu devant, 2 essieux derrière et semi-remorque avec 4 essieux)",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],


            [
                "name" => "12 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "15 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "20 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "25 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "30 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "40 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "50 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "60 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "70 places",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Fourchon",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Plateau",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Citerne",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Benne",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "Bus",
                "image" => "https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png"
            ],
            [
                "name" => "70 places",
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
            [
                "name" => "Livré",
                "description" => "Ce Fret a été livré",
            ],
        ];

        foreach ($fret_status as $fret_statu) {
            \App\Models\FretStatus::factory()->create($fret_statu);
        }


        #=========== CREER DES TYPES DE MARCHANDISE PAR DEFAUT ============#

        $marchandise_types = [
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

        foreach ($marchandise_types as $marchandise_type) {
            \App\Models\MarchandiseType::factory()->create($marchandise_type);
        }
    }
}
