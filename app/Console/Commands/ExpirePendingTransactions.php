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
    protected $description = 'Mengecek dan membatalkan transaksi DRAFT yang sudah melewati batas waktu expired_at, lalu mengembalikan kuota (sold_count).';

    public function handle()
    {
        // Hanya expire transaksi DRAFT — PENDING berarti sudah upload bukti bayar, tidak boleh di-expire otomatis
        $expiredTransactions = Transaction::with('transactionItems.ticketCategory')
            ->where('transaction_status', TransactionStatusEnum::DRAFT->value)
            ->where('expired_at', '<', now())
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info('Tidak ada transaksi expired saat ini.');
            return;
        }

        $count = 0;

        foreach ($expiredTransactions as $transaction) {
            DB::transaction(function () use ($transaction, &$count) {
                $transaction->update([
                    'transaction_status' => TransactionStatusEnum::EXPIRED->value,
                    'cancel_reason'      => 'Expired by System (Timeout)',
                ]);

                foreach ($transaction->transactionItems as $item) {
                    if ($item->ticketCategory) {
                        $item->ticketCategory->decrement('sold_count', $item->quantity);
                    }
                }

                $count++;
            });
        }

        Log::info("Scheduler berjalan: {$count} transaksi DRAFT berhasil di-expired dan kuota dikembalikan.");
        $this->info("Berhasil membatalkan {$count} transaksi.");
    }
}
