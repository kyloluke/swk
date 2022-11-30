<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
  
        DB::table('admins')->insert([
               [
                    'name'      => '管理者',
                    'username'  => 'admin',
                    'email'     => 'admin@shachihata.com',
                    'email_verified_at' => date('Y-m-d H:i:s'),
  
                    'password' => Hash::make('swk123'),
  
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s') 
                 ]
            ]);
    }
}
