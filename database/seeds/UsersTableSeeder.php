<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create();
        factory(User::class)->create([
            'username' => 'ivonightgod',
            'description' => 'Ivonei Freitas',
            'password' => 'designpatterns2017',
            'remember_token' => 'this_is_token',
        ]);
    }
}
