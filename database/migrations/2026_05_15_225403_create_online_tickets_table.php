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
        Schema::create('online_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Full Online Pass"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->double('price')->default(0);
            
            // Periode kapan portal online ini bisa diakses (Misal: Hari H s/d H+5)
            $table->dateTime('access_start_date');
            $table->dateTime('access_end_date');
            
            $table->text('tnc')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_tickets');
    }
};
