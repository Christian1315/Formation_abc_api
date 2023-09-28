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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->text("reference");
            $table->foreignId('facturier')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreignId('client')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->text("facture");
            $table->boolean("paid")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
