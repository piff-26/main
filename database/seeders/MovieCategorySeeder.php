<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MovieCategory;

class MovieCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovieCategory::create([
            'name' => 'Gap In A Minute',
            'slug' => 'gap-in-a-minute', 
            'description' => 'One Minute Short Film Competition',
        ]);

        MovieCategory::create([
            'name' => 'Student Gap Standers',
            'slug' => 'student-gap-standers', 
            'description' => 'Student Film Competition',
        ]);

        MovieCategory::create([
            'name' => 'Voices In The Gap',
            'slug' => 'voices-in-the-gap', 
            'description' => 'Documentary Film Competition',
        ]);
    }
}
