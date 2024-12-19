<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email'=> 'guest@gmail.com',
            'password' => hash::make('test1234'),
            'role' => 'GUEST',
        ]);
        User::create([
            'email'=> 'staff@gmail.com',
            'password' => hash::make('staff1234'),
            'role' => 'STAFF',
        ]);
        User::create([
            'email'=> 'hstaff@gmail.com',
            'password' => hash::make('head_staff1234'),
            'role' => 'HEAD_STAFF',
        ]);
    }
}
