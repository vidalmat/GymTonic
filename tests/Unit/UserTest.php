<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_a_firstname_and_lastname()
    {
        $user = User::factory()->create([
            'lastname' => 'Dupont',
            'firstname' => 'Sébastien',
            'email' => 'sebastien_dupont@dupont.fr',
            'password' => Hash::make('password')
        ]);

        $this->assertEquals('Dupont', $user->lastname);
        $this->assertEquals('Sébastien', $user->firstname);
        $this->assertEquals('sebastien_dupont@dupont.fr', $user->email);
    }
}
