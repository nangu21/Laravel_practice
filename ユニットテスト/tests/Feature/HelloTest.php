<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Person;


class HelloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //public function test_example()
    //{
    //    $response = $this->get('/');
//
    //    $response->assertStatus(200);
    //}

    //use DatabaseMigrations; //laravel7以前
    use RefreshDatabase;

    public function testHello() {
        $this->assertTrue(true);

        //$arr = [];
        //$this->assertEmpty($arr);
//
        //$msg = "Hello";
        //$this->assertEquals('Hello', $msg);
//
        //$n = random_int(0, 100);
        //$this->assertLessThan(100, $n);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/home');
        $response->assertStatus(302);

        //$user = factory(User::class)->create(); laravel8ではエラー
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/home');
        $response->assertStatus(200);

        $response = $this->get('/no_route');
        $response->assertStatus(404);

        /*
        以下の作業：：laravel8以降はseederとfactoryで行う

        factory(User::class)->create([
            'name' => 'AAA',
            'email' => 'BBB@CCC.com',
            'password' => 'ABCABC',
        ]);
        factory(User::class, 10)->create();

        $this->assertDatabaseHas('users', [
            'name' => 'AAA',
            'email' => 'BBB@CCC.com',
            'password' => 'ABCABC',
        ]);

        factory(Person::class)->create([
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);
        factory(Person::class, 10)->create();

        $this->assertDatabaseHas('people', [
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);*/
    }

}