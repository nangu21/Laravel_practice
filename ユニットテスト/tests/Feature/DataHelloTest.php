<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Person;

class DataHelloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_data()
    {
        Person::factory()->create([
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);

        $this->assertDatabaseHas('people', [
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);
    }
}
