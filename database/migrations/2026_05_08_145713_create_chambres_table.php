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
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->enum('type', ['simple', 'double', 'suite', 'familiale', 'presidentielle']);
            $table->integer('etage')->default(0);
            $table->integer('capacite')->default(1);
            $table->decimal('prix_nuit', 10, 2);
            $table->enum('statut', ['disponible', 'occupee', 'maintenance', 'nettoyage'])->default('disponible');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
