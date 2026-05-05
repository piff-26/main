<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\TicketCategory;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Day 1
        $day1 = Event::create([
            'name' => 'Screening Session-Student Gap Standers',
            'slug' => 'piff-day1',
            'location' => 'Auditorium of Q Building, Petra Christian University',
            'event_date' => '2026-05-29',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
        ]);

        TicketCategory::create(['event_id' => $day1->id, 'name' => 'Regular', 'slug' => 'regular', 'price' => 20000, 'quota' => 230]);

        // Day 2
        $day2 = Event::create([
            'name' => 'Final Day and Talkshow With Chandra Liow',
            'slug' => 'piff-day2',
            'location' => 'Auditorium of Q Building, Petra Christian University',
            'event_date' => '2026-05-30', 
            'start_time' => '12:00:00',
            'end_time' => '15:00:00',
        ]);

        TicketCategory::create(['event_id' => $day2->id, 'name' => 'Platinum', 'slug' => 'platinum', 'price' => 59000, 'quota' => 150]);
        TicketCategory::create(['event_id' => $day2->id, 'name' => 'Gold', 'slug' => 'gold', 'price' => 45000, 'quota' => 200]);
        TicketCategory::create(['event_id' => $day2->id, 'name' => 'Silver', 'slug' => 'silver', 'price' => 39000, 'quota' => 250]);
    }
}
