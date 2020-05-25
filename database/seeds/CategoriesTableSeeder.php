<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 10)->create()
            ->each(function ($cat) {
                $cat->products()->saveMany(factory(Product::class, 10)->make()); //Pra cada categoria, adiciona 10 produtos
            });
    }
}
