<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Lesson;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $labels = ['Cardio', 'Zumba', 'Dance'];

        $startDate = now();
        $endDate = now()->addMonth();

        $currentDate = $startDate;

        while ($currentDate <= $endDate) {

            $start = Carbon::create($currentDate->year, $currentDate->month, $currentDate->day)
                ->addHours(rand(8, 19))
                ->addMinutes(rand(0, 59));
            $end = $start->copy()->addHour();

            Lesson::create([
                'user_id' => '792dd775-e67f-44c3-bd07-7ae5bcafbbdd',
                'label' => $labels[array_rand($labels)],
                'duration' => 60,
                'start' => $start,
                'end' => $end
            ]);

            $currentDate->addDay();
        }
    }
}
