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
        Schema::create('sejours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('restrict');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->json('extras')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('statut_paiement', ['en_attente', 'partiel', 'paye'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sejours');
    }
};
