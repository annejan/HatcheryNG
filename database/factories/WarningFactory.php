<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Warning;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warning::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => function () {
                return Project::factory();
            },
            'description' => $this->faker->paragraph,
        ];
    }
}
