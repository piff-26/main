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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->enum('discount_type', ['nominal', 'percentage'])->default('nominal');
            $table->double('discount_nominal')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->foreignUuid('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignUuid('ticket_category_id')->nullable()->constrained('ticket_categories')->nullOnDelete();
            $table->integer('max_uses');
            $table->integer('used_count')->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
