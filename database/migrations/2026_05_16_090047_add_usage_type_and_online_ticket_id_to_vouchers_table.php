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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('usage_type', ['offline_only', 'online_only', 'all'])->default('all')->after('discount_percentage');
            $table->foreignId('online_ticket_id')->nullable()->after('ticket_category_id')->constrained('online_tickets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropForeign(['online_ticket_id']);
            $table->dropColumn(['usage_type', 'online_ticket_id']);
        });
    }
};
