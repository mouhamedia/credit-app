<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            
            // Relation vers le client
            $table->unsignedBigInteger('client_id');

            // Boutiquier qui enregistre le crédit (nullable car SET NULL)
            $table->unsignedBigInteger('created_by')->nullable();

            // Données du crédit
            $table->string('article');
            $table->decimal('montant', 10, 2);

            $table->timestamps();

            // FK vers clients
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');

            // FK vers users (boutiquiers)
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
