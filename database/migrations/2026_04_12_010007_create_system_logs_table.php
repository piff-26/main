<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');           // email, ticket, checkin
            $table->string('status');         // success, failed, info
            $table->string('message');
            $table->string('reference')->nullable();  // invoice_code, ticket_code, etc
            $table->json('context')->nullable();      // extra data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
