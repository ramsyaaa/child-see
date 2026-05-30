<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = [
            [
                'name' => 'Sari Wijaya',
                'specialization' => 'Yoga & Meditation',
                'bio' => 'Certified yoga instructor with 8 years of experience. Specializes in Hatha, Vinyasa, and meditation practices. Passionate about helping students find balance and inner peace.',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'specialization' => 'HIIT & Functional Training',
                'bio' => 'Former athlete turned fitness coach. Expert in high-intensity training and functional movements. Motivates clients to push their limits safely.',
                'is_active' => true,
            ],
            [
                'name' => 'Dewi Lestari',
                'specialization' => 'Pilates & Barre',
                'bio' => 'Pilates master trainer with international certification. Specializes in reformer Pilates and barre workouts. Focuses on proper form and mind-body connection.',
                'is_active' => true,
            ],
            [
                'name' => 'Andi Pratama',
                'specialization' => 'Indoor Cycling & Cardio',
                'bio' => 'Energetic cycling instructor with a passion for music and motivation. Creates challenging yet fun cycling experiences for all fitness levels.',
                'is_active' => true,
            ],
            [
                'name' => 'Maya Kusuma',
                'specialization' => 'Zumba & Dance Fitness',
                'bio' => 'Professional dancer and Zumba instructor. Brings joy and energy to every class with infectious enthusiasm and creative choreography.',
                'is_active' => true,
            ],
            [
                'name' => 'Rudi Hermawan',
                'specialization' => 'Boxing & Combat Fitness',
                'bio' => 'Former competitive boxer with 10 years of training experience. Teaches proper boxing techniques while providing an intense cardio workout.',
                'is_active' => true,
            ],
            [
                'name' => 'Lina Anggraini',
                'specialization' => 'Prenatal & Postnatal Yoga',
                'bio' => 'Specialized in yoga for expecting and new mothers. Certified prenatal yoga instructor with a gentle, nurturing teaching approach.',
                'is_active' => true,
            ],
            [
                'name' => 'Fajar Nugroho',
                'specialization' => 'Core Strength & Conditioning',
                'bio' => 'Sports science graduate specializing in core training and athletic conditioning. Helps clients build a strong foundation for all physical activities.',
                'is_active' => true,
            ],
            [
                'name' => 'Rina Sari',
                'specialization' => 'Stretching & Flexibility',
                'bio' => 'Flexibility specialist and former gymnast. Focuses on improving mobility, preventing injuries, and enhancing overall movement quality.',
                'is_active' => true,
            ],
            [
                'name' => 'Dimas Prasetyo',
                'specialization' => 'Power Yoga & Vinyasa',
                'bio' => 'Dynamic yoga instructor combining traditional practices with modern fitness. Creates challenging flows that build strength and flexibility.',
                'is_active' => true,
            ],
        ];

        foreach ($instructors as $instructor) {
            Instructor::updateOrCreate(['name' => $instructor['name']], $instructor);
        }
    }
}

