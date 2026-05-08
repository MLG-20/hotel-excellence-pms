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
            $table->string('numero')->unique();
            $table->foreignId('sejour_id')->constrained('sejours')->onDelete('restrict');
            $table->decimal('nuitees', 10, 2)->default(0);
            $table->decimal('extras', 10, 2)->default(0);
            $table->decimal('remise', 10, 2)->default(0);
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('tva', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);
            $table->enum('statut', ['brouillon', 'emise', 'payee', 'annulee'])->default('brouillon');
            $table->timestamp('date_emission')->nullable();
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
