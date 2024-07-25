<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $categories = Category::whereIn('group_by', ['item'])->get();

        $item = $categories->filter(function($x) {
            return $x->group_by == 'item';
        });

        $items = $item->random();

        return [
            'category_id' => $items->id,
            'ref_no' => fake()->unique()->randomNumber(6),
            'name' => fake()->name()
        ];
    }
}
