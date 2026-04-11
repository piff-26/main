<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->json('holder_names')->nullable()->after('price');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('holder_name')->nullable()->after('ticket_code');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('holder_names');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('holder_name');
        });
    }
};
