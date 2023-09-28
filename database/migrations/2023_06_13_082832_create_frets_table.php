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

            $table->string('depart_map')->nullable();
            $table->string('arrived_map')->nullable();

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

            ###MOYEN DE TRANSPORT ASSOCIE
            $table->foreignId('transport_id')
                ->nullable()
                ->constrained("transports", "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->boolean("factured")->default(false);
            $table->boolean("affected")->default(false);
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
