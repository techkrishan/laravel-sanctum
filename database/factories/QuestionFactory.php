<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\{User, Lookup};
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->text(255),
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Lookup::where(['lookup_type' => config('lookups.lookup_type.question_type.slug')])->inRandomOrder()->first()->id,
            'is_active' => true,
        ];
    }
}
