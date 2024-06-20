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
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $faker = Faker::create();

        $documentIds = [];
        foreach (range(1, 10) as $index) {
            $document = Document::create([
                'id' => $faker->uuid,
                'member_charter' => $faker->boolean(),
                'registration_form' => $faker->boolean(),
                'cover_letter' => $faker->boolean(),
                'partner_document' => $faker->boolean(),
                'medical_certificat' => $faker->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $documentIds[] = $document->id;
        }

        foreach (range(1, 50) as $index) {
            Member::create([
                'id' => $faker->uuid,
                'lastname' => addslashes($faker->lastName), // Échappement manuel si nécessaire
                'firstname' => $faker->firstName,
                'email' => $faker->email,
                'document_id' => $faker->uuid,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }
}
