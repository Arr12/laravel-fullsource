<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Administrator',
            'username' => 'administrator',
            'email' => 'aryo.puruhito12@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        for ($i=0; $i < 100; $i++) {
            User::factory()->create([
                'id' => Uuid::uuid4()->toString(),
                'name' => 'User - ' . date('Ymd') . sprintf("%09d", $i),
                'username' => 'user' . date('Ymd') . sprintf("%09d", $i),
                'email' => 'user' . date('Ymd') . sprintf("%09d", $i) . '@example.com',
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}