<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use App\Models\ArtistDiscogModel;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $records = ArtistDiscogModel::whereNull('artist_description')->get();

        foreach ($records as $record) {
            $record->update([
                'artist_description' => $faker->text(300),
                'album_description' => $faker->text(300), 
            ]);
        }
    }
}
