<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterClass;

class MasterClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterClasses = [
            [
                'name' => 'Hatha Yoga',
                'category' => 'Yoga',
                'description' => 'Traditional yoga practice focusing on physical postures and breathing techniques. Perfect for beginners and those seeking a gentle introduction to yoga.',
                'default_duration' => 60,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#97B5A9', // Sage Green
            ],
            [
                'name' => 'Vinyasa Flow',
                'category' => 'Yoga',
                'description' => 'Dynamic yoga style linking breath with movement. Builds strength, flexibility, and mindfulness through flowing sequences.',
                'default_duration' => 75,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#4CAF50', // Green
            ],
            [
                'name' => 'Power Yoga',
                'category' => 'Yoga',
                'description' => 'Intense, fitness-based yoga approach. Combines strength training with traditional yoga poses for a challenging full-body workout.',
                'default_duration' => 60,
                'difficulty_level' => 'advanced',
                'is_active' => true,
                'color' => '#2E7D32', // Dark Green
            ],
            [
                'name' => 'Pilates Mat',
                'category' => 'Pilates',
                'description' => 'Core-strengthening exercises performed on a mat. Improves posture, flexibility, and overall body awareness.',
                'default_duration' => 60,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#E3B7B4', // Soft Rose Pink
            ],
            [
                'name' => 'Reformer Pilates',
                'category' => 'Pilates',
                'description' => 'Advanced Pilates using specialized equipment. Provides resistance training for enhanced muscle toning and flexibility.',
                'default_duration' => 60,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#D81B60', // Pink
            ],
            [
                'name' => 'Zumba Dance',
                'category' => 'Dance',
                'description' => 'High-energy dance fitness class featuring Latin and international music. Fun cardio workout that feels like a party!',
                'default_duration' => 60,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#F5DD89', // Light Pastel Yellow
            ],
            [
                'name' => 'HIIT Training',
                'category' => 'Cardio',
                'description' => 'High-Intensity Interval Training combining short bursts of intense exercise with recovery periods. Maximum calorie burn in minimal time.',
                'default_duration' => 45,
                'difficulty_level' => 'advanced',
                'is_active' => true,
                'color' => '#FF6F51', // Warm Coral
            ],
            [
                'name' => 'Indoor Cycling',
                'category' => 'Cardio',
                'description' => 'Energetic group cycling class with motivating music. Great cardio workout that builds endurance and leg strength.',
                'default_duration' => 45,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#FF9800', // Orange
            ],
            [
                'name' => 'Boxing Fitness',
                'category' => 'Combat',
                'description' => 'Combat-inspired workout combining boxing techniques with cardio. Builds strength, speed, and confidence.',
                'default_duration' => 60,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#E65A3D', // Coral Hover
            ],
            [
                'name' => 'Barre Workout',
                'category' => 'Dance',
                'description' => 'Ballet-inspired fitness class using a barre. Tones muscles, improves posture, and increases flexibility.',
                'default_duration' => 60,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#FFB8A5', // Coral Light
            ],
            [
                'name' => 'Stretching & Mobility',
                'category' => 'Wellness',
                'description' => 'Gentle class focused on improving flexibility and range of motion. Perfect for recovery and injury prevention.',
                'default_duration' => 45,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#9C27B0', // Purple
            ],
            [
                'name' => 'Core Strength',
                'category' => 'Strength',
                'description' => 'Targeted workout focusing on abdominal and back muscles. Builds a strong, stable core for better overall fitness.',
                'default_duration' => 45,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#3F51B5', // Indigo
            ],
            [
                'name' => 'Meditation & Mindfulness',
                'category' => 'Wellness',
                'description' => 'Guided meditation and breathing exercises. Reduces stress, improves focus, and promotes mental well-being.',
                'default_duration' => 30,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#00BCD4', // Cyan
            ],
            [
                'name' => 'Functional Training',
                'category' => 'Strength',
                'description' => 'Movement-based exercises that improve everyday activities. Combines strength, balance, and coordination training.',
                'default_duration' => 60,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'color' => '#607D8B', // Blue Grey
            ],
            [
                'name' => 'Prenatal Yoga',
                'category' => 'Yoga',
                'description' => 'Gentle yoga designed for expecting mothers. Safely strengthens the body and prepares for childbirth.',
                'default_duration' => 60,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'color' => '#FFCDD2', // Light Pink
            ],
        ];

        foreach ($masterClasses as $class) {
            MasterClass::updateOrCreate(['name' => $class['name']], $class);
        }
    }
}

