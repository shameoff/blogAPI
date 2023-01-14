<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userSeeder = new UserDatabaseSeeder;
        $userSeeder->run();
        $tagSeeder = new TagDatabaseSeeder;
        $tagSeeder->run();
        $postSeeder = new PostDatabaseSeeder;
        $postSeeder->run();


    }
}
