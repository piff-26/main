<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Enums\TransactionStatusEnum;
use App\Enums\SourceInfoEnum;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional users
        $users = [];
        $users[] = User::create(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $users[] = User::create(['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => bcrypt('password')]);
        $users[] = User::create(['name' => 'Bob Wilson', 'email' => 'bob@example.com', 'password' => bcrypt('password')]);
        $users[] = User::create(['name' => 'Alice Brown', 'email' => 'alice@example.com', 'password' => bcrypt('password')]);
        $users[] = User::create(['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'password' => bcrypt('password')]);

        $categories = TicketCategory::all();
        $vouchers = Voucher::all();
        $cities = ['Surabaya', 'Jakarta', 'Bandung', 'Semarang', 'Yogyakarta'];
        $sources = array_column(SourceInfoEnum::cases(), 'value');
        
        // Create paid transactions with varied items
        for ($i = 1; $i <= 15; $i++) {
            $user = $users[array_rand($users)];
            
            // Randomly choose 1-3 different categories
            $numCategories = rand(1, 3);
            $selectedCategories = $categories->random($numCategories);
            
            // 30% chance to use voucher
            $voucher = null;
            if ($vouchers->count() > 0 && rand(0, 100) < 30) {
                $voucher = $vouchers->random();
            }
            
            $totalAmount = 0;
            $items = [];
            
            foreach ($selectedCategories as $category) {
                $quantity = rand(1, 2);
                $totalAmount += $category->price * $quantity;
                $items[] = [
                    'category' => $category,
                    'quantity' => $quantity
                ];
            }
            
            $transaction = Transaction::create([
                'id'                 => Str::uuid(),
                'invoice_code'       => 'INV-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'user_id'            => $user->id,
                'buyer_name'         => $user->name,
                'buyer_phone'        => '+62' . rand(800000000, 899999999),
                'city'               => $cities[array_rand($cities)],
                'source_info'        => $sources[array_rand($sources)],
                'total_amount'       => $totalAmount,
                'voucher_id'         => $voucher?->id,
                'transaction_status' => TransactionStatusEnum::PAID->value,
                'paid_at'            => now()->subDays(rand(1, 30)),
                'agree_tnc'          => true,
                'created_at'         => now()->subDays(rand(1, 30)),
            ]);

            // Create transaction items and tickets for each category
            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'ticket_category_id' => $item['category']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['category']->price,
                ]);

                // Update sold count
                $item['category']->increment('sold_count', $item['quantity']);

                // Generate tickets
                for ($j = 0; $j < $item['quantity']; $j++) {
                    $isCheckedIn = rand(0, 100) < 40; // 40% checked in
                    Ticket::create([
                        'id' => Str::uuid(),
                        'transaction_id' => $transaction->id,
                        'ticket_category_id' => $item['category']->id,
                        'ticket_code' => 'TKT-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-' . $item['category']->id . '-' . str_pad($j + 1, 2, '0', STR_PAD_LEFT),
                        'is_checked_in' => $isCheckedIn,
                        'checked_in_at' => $isCheckedIn ? now()->subDays(rand(0, 5)) : null,
                    ]);
                }
            }
        }

        // Create draft transactions (no tickets generated)
        for ($i = 16; $i <= 20; $i++) {
            $user = $users[array_rand($users)];
            $category = $categories->random();
            $quantity = rand(1, 2);
            
            $transaction = Transaction::create([
                'id'                 => Str::uuid(),
                'invoice_code'       => 'INV-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'user_id'            => $user->id,
                'buyer_name'         => $user->name,
                'buyer_phone'        => '+62' . rand(800000000, 899999999),
                'city'               => $cities[array_rand($cities)],
                'source_info'        => $sources[array_rand($sources)],
                'total_amount'       => $category->price * $quantity,
                'transaction_status' => TransactionStatusEnum::DRAFT->value,
                'agree_tnc'          => true,
                'created_at'         => now()->subDays(rand(1, 10)),
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'ticket_category_id' => $category->id,
                'quantity' => $quantity,
                'price' => $category->price,
            ]);
        }

        // Create failed/expired transactions (no tickets generated)
        for ($i = 21; $i <= 25; $i++) {
            $user = $users[array_rand($users)];
            $category = $categories->random();
            $quantity = rand(1, 2);
            
            $transaction = Transaction::create([
                'id'                 => Str::uuid(),
                'invoice_code'       => 'INV-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'user_id'            => $user->id,
                'buyer_name'         => $user->name,
                'buyer_phone'        => '+62' . rand(800000000, 899999999),
                'city'               => $cities[array_rand($cities)],
                'source_info'        => $sources[array_rand($sources)],
                'total_amount'       => $category->price * $quantity,
                'transaction_status' => rand(0, 1) ? TransactionStatusEnum::FAILED->value : TransactionStatusEnum::EXPIRED->value,
                'agree_tnc'          => true,
                'created_at'         => now()->subDays(rand(10, 30)),
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'ticket_category_id' => $category->id,
                'quantity' => $quantity,
                'price' => $category->price,
            ]);
        }
        // Create pending transactions (uploaded payment proof, awaiting verification)
        for ($i = 26; $i <= 30; $i++) {
            $user = $users[array_rand($users)];
            $category = $categories->random();
            $quantity = rand(1, 2);

            $transaction = Transaction::create([
                'id'                 => Str::uuid(),
                'invoice_code'       => 'INV-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'user_id'            => $user->id,
                'buyer_name'         => $user->name,
                'buyer_phone'        => '+62' . rand(800000000, 899999999),
                'city'               => $cities[array_rand($cities)],
                'source_info'        => $sources[array_rand($sources)],
                'total_amount'       => $category->price * $quantity,
                'transaction_status' => TransactionStatusEnum::PENDING->value,
                'payment_proof'      => null,
                'agree_tnc'          => true,
                'created_at'         => now()->subHours(rand(1, 48)),
            ]);

            TransactionItem::create([
                'transaction_id'     => $transaction->id,
                'ticket_category_id' => $category->id,
                'quantity'           => $quantity,
                'price'              => $category->price,
            ]);

            $category->increment('sold_count', $quantity);
        }
    }
}