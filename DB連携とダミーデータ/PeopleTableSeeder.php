<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person; //使うモデル

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() //DBに追加する処理
    {
        \App\Models\Person::factory(20)->create();
    }
}
