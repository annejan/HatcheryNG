<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'version_id' => function () {
                return Version::factory();
            },
            'name'    => $this->faker->word.'.py',
            'content' => $this->faker->paragraph,
        ];
    }
}
