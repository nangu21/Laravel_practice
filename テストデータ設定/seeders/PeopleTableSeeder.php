<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Person; //使いたいモデル

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() //DBに追加する処理を以下に記述
    {
        /* $param = [
            'name' => 'taro',
            'mail' => 'taro@taro',
            'age' => 12,
        ];
        DB::table('people')->insert($param);

        $param = [
            'name' => 'hanako',
            'mail' => 'hanako@hanako',
            'age' => 24,
        ];
        DB::table('people')->insert($param);

        $param = [
            'name' => 'sachi',
            'mail' => 'sachi@sachi',
            'age' => 27,
        ];
        DB::table('people')->insert($param); */

        \App\Models\Person::factory()->count(10)->create();
    }
}
