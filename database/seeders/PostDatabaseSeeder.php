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
            'author_id' => '9839bd3a-00b8-4d37-947c-75750cb94721',
            'readingTime' => rand(1, 360),
            'photoPath' => fake()->filePath(),
        ]);
    }
}
