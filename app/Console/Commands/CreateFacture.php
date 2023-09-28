<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\V1\FACTURE_HELPER;
use App\Models\User;
use Illuminate\Console\Command;

class CreateFacture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:createfacture';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Cette commande permet de Créer automatiquement les factures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = User::all();
        foreach ($clients as $client) {
           FACTURE_HELPER::createFacture($client->id);
        }
    }
}
