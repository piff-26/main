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
        Schema::table('transaction_items', function (Blueprint $table) {
            // Make ticket_category_id nullable
            $table->uuid('ticket_category_id')->nullable()->change();
            
            // Add online_ticket_id
            $table->foreignId('online_ticket_id')->nullable()->constrained('online_tickets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['online_ticket_id']);
            $table->dropColumn('online_ticket_id');
            
            // Revert ticket_category_id to not nullable
            $table->uuid('ticket_category_id')->nullable(false)->change();
        });
    }
};
