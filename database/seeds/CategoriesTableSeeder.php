<?php

use App\Category;
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
        $data = [
            [
                'id' => 1,
                'name' => 'Music',
            ],
            [
                'id' => 2,
                'name' => 'Sports'
            ],
            [
                'id' => 3,
                'name' => 'Games'
            ],
            [
                'id' => 4,
                'name' => 'Movies'
            ],
            [
                'id' => 5,
                'name' => 'News'
            ],
            [
                'id' => 6,
                'name' => 'Live'
            ]
        ];

        foreach($data as $item) {
            Category::create($item);
        }
    }
}
