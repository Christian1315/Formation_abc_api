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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId('owner')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreignId('type_id')
                ->nullable()
                ->constrained('types', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->string('fabric_year');
            $table->string('circulation_year');
            $table->string('assurance_expire');
            $table->string('tech_visit_expire');

            $table->string('gris_card');
            $table->string('assurance_card');

            $table->string('img1');
            $table->string('img2');
            $table->string('img3');

            $table->foreignId('status')
                ->nullable()
                ->constrained("transport_statuses", "id")
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
        Schema::dropIfExists('transports');
    }
};
