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
        Schema::create('room_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sejour_id')->constrained('sejours')->onDelete('cascade');
            $table->json('articles');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('heure_commande')->nullable();
            $table->enum('statut', ['en_attente', 'en_preparation', 'livre', 'annule'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_services');
    }
};
