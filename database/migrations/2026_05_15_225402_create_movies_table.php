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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('movie_categories')->cascadeOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable(); // Gambar poster
            $table->string('video_url')->nullable(); // Link vimeo/youtube unlisted
            
            // Fitur khusus untuk sesi ITEM (Live Streaming)
            $table->boolean('is_live')->default(false);
            $table->dateTime('scheduled_at')->nullable(); // Jadwal live
            
            $table->boolean('is_active')->default(true); // Toggle on/off film
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
