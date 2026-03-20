<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Enums\TransactionStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpirePendingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek dan membatalkan transaksi pending yang sudah melewati batas waktu expired_at, lalu mengembalikan kuota (sold_count).';

    /**
     * Execute the console command.
     */
    public function handle()
        {
            // Cari semua transaksi pending yang waktu expired_at-nya sudah lewat dari waktu sekarang
            $expiredTransactions = Transaction::with('transactionItems.ticketCategory')
                ->where('transaction_status', TransactionStatusEnum::PENDING->value)
                ->where('expired_at', '<', now())
                ->get();

            if ($expiredTransactions->isEmpty()) {
                $this->info('Tidak ada transaksi expired saat ini.');
                return;
            }

            $count = 0;

            foreach ($expiredTransactions as $transaction) {
                // Gunakan DB Transaction per item agar aman
                DB::transaction(function () use ($transaction, &$count) {
                    // Ubah status transaksi jadi expired
                    $transaction->update([
                        'transaction_status' => TransactionStatusEnum::EXPIRED->value,
                        'cancel_reason' => 'Expired by System (Timeout)'
                    ]);

                    // Kembalikan kuota dengan mengurangi sold_count
                    foreach ($transaction->transactionItems as $item) {
                        if ($item->ticketCategory) {
                            $item->ticketCategory->decrement('sold_count', $item->quantity);
                        }
                    }
                    
                    $count++;
                });
            }

            // Catat ke log Laravel
            Log::info("Scheduler berjalan: {$count} transaksi berhasil di-expired dan kuota dikembalikan.");
            $this->info("Berhasil membatalkan {$count} transaksi.");
        }
}
