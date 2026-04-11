<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SourceInfoEnum;
use App\Enums\TransactionStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->string('buyer_name')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('city')->nullable();
            $table->enum('source_info', array_column(SourceInfoEnum::cases(), 'value'))->nullable();
            
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->double('discount_amount')->default(0);
            $table->double('total_amount')->default(0);
            
            $table->enum('transaction_status', array_column(TransactionStatusEnum::cases(), 'value'))->default(TransactionStatusEnum::DRAFT->value);
            $table->string('payment_proof')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('cancel_reason')->nullable();
            
            $table->boolean('agree_tnc')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
