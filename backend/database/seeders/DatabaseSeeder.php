<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'college_id' => 'ADMIN001',
            'status' => 'approved',
            'role' => 'admin',
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'System Administrator for the Community Platform',
            'current_status' => 'Administrator',
            'skills' => ['Management', 'System Administration'],
        ]);

        // Create sample approved users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'college_id' => 'CS2024001',
                'profile' => [
                    'batch' => '2024',
                    'course' => 'Computer Science',
                    'skills' => ['PHP', 'Laravel', 'Vue.js', 'JavaScript'],
                    'bio' => 'Full-stack developer passionate about web technologies and open source.',
                    'current_status' => 'Final Year Student',
                    'location' => 'New York, USA',
                ]
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'college_id' => 'CS2023002',
                'profile' => [
                    'batch' => '2023',
                    'course' => 'Computer Science',
                    'skills' => ['Python', 'Django', 'React', 'Machine Learning'],
                    'bio' => 'AI enthusiast and software engineer. Love working on innovative projects.',
                    'current_status' => 'Software Engineer at TechCorp',
                    'location' => 'San Francisco, USA',
                ]
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'college_id' => 'EE2024003',
                'profile' => [
                    'batch' => '2024',
                    'course' => 'Electrical Engineering',
                    'skills' => ['Circuit Design', 'Arduino', 'IoT', 'C++'],
                    'bio' => 'Hardware enthusiast working on IoT projects and embedded systems.',
                    'current_status' => 'Student',
                    'location' => 'Boston, USA',
                ]
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'college_id' => $userData['college_id'],
                'status' => 'approved',
            ]);

            Profile::create(array_merge(['user_id' => $user->id], $userData['profile']));

            Post::create([
                'user_id' => $user->id,
                'title' => 'Welcome to the Community!',
                'content' => 'Hi everyone! Excited to be part of this amazing community. Looking forward to connecting with fellow students and alumni.',
                'type' => 'general',
            ]);
        }

        // Create sample pending user
        $pendingUser = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'college_id' => 'CS2024004',
            'status' => 'pending',
        ]);

        Profile::create([
            'user_id' => $pendingUser->id,
            'batch' => '2024',
            'course' => 'Computer Science',
            'bio' => 'Aspiring software developer interested in web development.',
        ]);

        // Create sample posts
        Post::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to Community Platform!',
            'content' => 'Welcome to our new community platform! This is a space for students, alumni, and graduates to connect, share knowledge, and grow together. Feel free to share job opportunities, ask questions, seek guidance, or just have general discussions.',
            'type' => 'general',
        ]);

        Post::create([
            'user_id' => User::where('email', 'john@example.com')->first()->id,
            'title' => 'Software Engineer Position at StartupXYZ',
            'content' => 'Hey everyone! My company is hiring for a junior software engineer position. We work with modern technologies like React, Node.js, and AWS. Great opportunity for recent graduates. DM me for more details!',
            'type' => 'job',
        ]);

        Post::create([
            'user_id' => User::where('email', 'sarah@example.com')->first()->id,
            'title' => 'Tips for Technical Interviews',
            'content' => 'Having gone through multiple technical interviews recently, here are some tips: 1) Practice coding problems daily 2) Understand system design basics 3) Be ready to explain your projects 4) Ask good questions about the company. Happy to help anyone preparing for interviews!',
            'type' => 'guidance',
        ]);
    }
}
