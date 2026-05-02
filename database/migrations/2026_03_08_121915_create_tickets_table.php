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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignUuid('ticket_category_id')->constrained('ticket_categories')->cascadeOnDelete();
            $table->string('ticket_code')->unique();
            
            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignUuid('checked_in_by')->nullable()->constrained('admins')->nullOnDelete();
            
            $table->boolean('is_canceled')->default(false);
            $table->timestamp('canceled_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
