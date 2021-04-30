<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
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
        $suppliers = Supplier::factory()->count(3)->create();
        $posts = Product::factory()
            ->count(30)
            ->for(Supplier::factory()->state([
                'name' => 'Jessica Archer',
            ]))
            ->for(Category::factory())
            ->create();
        $users = User::factory()->count(3)->create();
    }
}
