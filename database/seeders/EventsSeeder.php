<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Event; // Assurez-vous d'importer le modèle Event

class EventsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $labels = ['Cardio', 'Zumba', 'Dance'];

        foreach (range(1, 10) as $index) {
            Lesson::create([
                'user_id' => '9c647ade-eb1f-4b2e-8624-1c3755e1b149',
                'label' => $labels[array_rand($labels)],
                'duration' => 60,
                'start' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end' => $faker->dateTimeBetween('now', '+1 months')
            ]);
        }
    }
}
