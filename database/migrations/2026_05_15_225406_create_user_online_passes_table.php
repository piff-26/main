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
        Schema::create('user_online_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            
            // UUID karena tabel transactions kamu pakai UUID
            $table->foreignUuid('transaction_id')->constrained('transactions')->cascadeOnDelete();
            
            $table->foreignId('online_ticket_id')->constrained('online_tickets')->cascadeOnDelete();
            
            // Status jika misal user melanggar aturan dan aksesnya mau dicabut admin
            $table->enum('status', ['active', 'inactive'])->default('active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_online_passes');
    }
};
