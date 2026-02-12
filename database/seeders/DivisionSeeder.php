<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Steering Committee',
                'slug' => 'sc',
            ],
            [
                'name' => 'Badan Pengurus Harian',
                'slug' => 'bph',
            ],
            [
                'name' => 'Acara',
                'slug' => 'acara',
            ],
            [
                'name' => 'Transportasi, Perlengkapan dan Keamanan',
                'slug' => 'transkapman',
            ],
            [
                'name' => 'Creative',
                'slug' => 'creative',
            ],
            [
                'name' => 'Sekretariat dan Konsumsi',
                'slug' => 'sekkon',
            ],
            [
                'name' => 'Information Technology',
                'slug' => 'it',
            ],
            [
                'name' => 'Sponsorship dan Partnership',
                'slug' => 'sponsor',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create([
                'id' => Str::uuid(),
                'name' => $division['name'],
                'slug' => $division['slug'],
            ]);
        }
    }
}
