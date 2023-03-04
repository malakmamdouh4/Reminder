<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user =
            ['first_name' => 'user','last_name' => 'test', 'phone' => '1011221122', 'country_key' => '00966', 'gender' => 'male',
                'email' => 'user@example.com', 'password' => bcrypt('1011221122'),'type'=>'family','date_birth'=>'02-02-2020',
                'lat'=>'30.3333333333','long'=>'31.22222222','address'=>'mansoura','status'=>'active','lang'=>'ar'];

        User::create($user);
    }
}
