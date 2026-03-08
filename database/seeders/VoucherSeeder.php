<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\Event;
use App\Models\TicketCategory;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $piff = Event::where('slug', 'piff-day1')->first();
        $piffday2 = TicketCategory::where('slug', 'Platinum')->first();

        // Voucher Potongan Nominal Rp 20.000 (Khusus event PCE)
        Voucher::create([
            'code' => 'PIFF20K',
            'discount_type' => 'nominal',
            'discount_nominal' => 20000,
            'event_id' => $piff?->id,
            'max_uses' => 50,
            'used_count' => 0,
            'expired_at' => '2026-06-30 23:59:59',
            'status' => 'active',
        ]);

        // Voucher Diskon 10% (Khusus tiket VIP PIFF)
        Voucher::create([
            'code' => 'PIFFPLAT10',
            'discount_type' => 'percentage',
            'discount_percentage' => 10,
            'ticket_category_id' => $piffday2?->id,
            'max_uses' => 20,
            'used_count' => 0,
            'expired_at' => '2026-06-19 23:59:59',
            'status' => 'active',
        ]);
    }
}
