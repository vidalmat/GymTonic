<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Document;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Member::factory(50)->create();

        // \App\Models\User::factory()->create([
        //     'id' => '2206e037-1539-4aa3-b107-6fe7e52977db',
        //     'lastname' => 'Doe',
        //     'firstname' => 'John',
        //     'email' => 'vidalmat06@gmail.com',
        //     'password' => '$2y$12$iokZKWRkP8CDZ6RNH5OfduNNsUkSs2WZ/P5D.sEeBpPE1jRDWBXRS'
        // ]);

        $faker = Faker::create();

        // $documentIds = [];
        // foreach (range(1, 10) as $index) {
        //     $document = Document::create([
        //         'id' => $faker->uuid,
        //         'member_charter' => $faker->boolean(),
        //         'registration_form' => $faker->boolean(),
        //         'cover_letter' => $faker->boolean(),
        //         'partner_document' => $faker->boolean(),
        //         'medical_certificat' => $faker->boolean(),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        //     $documentIds[] = $document->id;
        // }

        foreach (range(1, 50) as $index) {
            Member::create([
                'id' => $faker->uuid,
                'lastname' => addslashes($faker->lastName), // Échappement manuel si nécessaire
                'firstname' => $faker->firstName,
                'email' => $faker->email,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }
}
