<?php

namespace Database\Factories;

use App\Models\Person; //使いたいモデル
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Person::class; //Appの前のスラッシュないとエラー

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(), //nameカラムにname型のデータをいれる
            'mail' => $this->faker->unique()->safeEmail(),
            'age' => $this->faker->numberBetween(1, 99),
        ];
    }
}
