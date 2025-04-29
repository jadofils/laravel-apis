<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //seeding by using factories
        Student::factory()->count(5)->create();
        // Seeding 5 students manually
        Student::create([
            'name' => 'John Doe',
            'email' => 'VhUo8@example.com',
            'age' => 20,
            'phone' => '1234567890',
            'address' => '123 Main St',
            'image' => 'https://example.com/image1.jpg',
            'password' => bcrypt('password123'),
        ]);

        Student::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'age' => 22,
            'phone' => '0987654321',
            'address' => '456 Elm St',
            'image' => 'https://example.com/image2.jpg',
            'password' => bcrypt('password123'),
        ]);

        Student::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'age' => 19,
            'phone' => '5555555555',
            'address' => '789 Oak St',
            'image' => 'https://example.com/image3.jpg',
            'password' => bcrypt('password123'),
        ]);

        Student::create([
            'name' => 'Bob Brown',
            'email' => 'bob@example.com',
            'age' => 21,
            'phone' => '1112223333',
            'address' => '101 Pine St',
            'image' => 'https://example.com/image4.jpg',
            'password' => bcrypt('password123'),
        ]);

        Student::create([
            'name' => 'Emily Davis',
            'email' => 'emily@example.com',
            'age' => 23,
            'phone' => '9998887777',
            'address' => '202 Maple St',
            'image' => 'https://example.com/image5.jpg',
            'password' => bcrypt('password123'),
        ]);
    }
}
