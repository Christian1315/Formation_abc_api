<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('frets', function (Blueprint $table) {
            $table->id();
            ##TRAJET
            $table->string('depart_date');
            $table->string('arrived_date');

            $table->string('chargement_date');
            $table->string('delivery_date');

            $table->string('chargement_hour');
            $table->string('delivery_hour');

            ###MARCHANDISES
            $table->foreignId('fret_types')
                ->nullable()
                ->constrained('types', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('weight');
            $table->integer('length');

            ###VEHICULE
            $table->integer('transport_num');
            $table->foreignId('transport_type')
                ->nullable()
                ->constrained('types', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            ###PRIX
            $table->integer('price');

            ###PRIX
            $table->text('comment');


            ###EXPEDITEUR
            $table->foreignId('owner')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            ###STATUS DU FRET
            $table->foreignId('status')
                ->nullable()
                ->constrained("fret_statuses", "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frets');
    }
};
