<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
class PostDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(10)->create([
            'title' => fake()->title(),
            'content' => fake()->text(),
            'author_id' => '984d6455-2c1b-45f7-a0ac-591530b15d81',
            'readingTime' => rand(1, 360),
            'photoPath' => fake()->filePath(),
        ]);
    }
}
