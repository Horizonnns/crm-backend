<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Request $request): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phonenum' => '90000000',
            'role' => 'admin',
            'password' => Hash::make($request->input('admin123')),
        ])->assignRole('admin');
    }
}
