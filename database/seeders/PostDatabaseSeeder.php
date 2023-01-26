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
        Post::factory(5)->create([
            'title' => fake()->title(),
            'content' => fake()->text(),
            'author_id' => '984ff49c-c55e-4eaf-b6f1-805c1c6d85b5',
            'readingTime' => rand(1, 360),
            'photoPath' => fake()->filePath(),
        ]);
    }
}
