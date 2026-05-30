<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterClass;
use App\Models\Instructor;
use App\Models\BatchClass;
use App\Models\Room;
use Carbon\Carbon;

class UpdatedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BatchClass::truncate();
        Instructor::truncate();
        MasterClass::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Cleared existing data..." . PHP_EOL;

        // Create Master Classes
        $yoga = MasterClass::create([
            'name' => 'Yoga',
            'category' => 'Mind & Body',
            'description' => 'A holistic practice combining physical postures, breathing techniques, and meditation to improve flexibility, strength, and mental well-being.',
            'default_duration' => 60,
            'difficulty_level' => 'beginner',
            'color' => '#97B5A9', // Sage Green
            'is_active' => true,
        ]);

        $matPilates = MasterClass::create([
            'name' => 'Mat Pilates',
            'category' => 'Strength & Conditioning',
            'description' => 'A low-impact workout focusing on core strength, flexibility, and body awareness using controlled movements on a mat.',
            'default_duration' => 60,
            'difficulty_level' => 'beginner',
            'color' => '#E3B7B4', // Soft Rose Pink
            'is_active' => true,
        ]);

        echo "Created Master Classes: Yoga, Mat Pilates" . PHP_EOL;

        // Create Yoga Instructors
        $yogaInstructorNames = [
            'Tiara',
            'Galuh',
            'Septi',
            'Meisy',
            'Putti',
            'Nirwa',
            'Ine',
            'Meilita',
            'Rio',
            'Ibi',
        ];

        $yogaInstructorModels = [];
        foreach ($yogaInstructorNames as $name) {
            $instructor = Instructor::create([
                'name' => $name,
                'specialization' => 'Yoga',
                'bio' => "Certified Yoga instructor specializing in various yoga styles and mindfulness practices.",
                'is_active' => true,
            ]);
            $yogaInstructorModels[] = $instructor;
            echo "Created Yoga Instructor: {$name}" . PHP_EOL;
        }

        // Create Mat Pilates Instructor
        $mimiInstructor = Instructor::create([
            'name' => 'Mimi',
            'specialization' => 'Mat Pilates',
            'bio' => "Certified Mat Pilates instructor with expertise in core strengthening and body alignment.",
            'is_active' => true,
        ]);

        echo "Created Mat Pilates Instructor: Mimi" . PHP_EOL;

        // Get or create a default room
        $room = Room::first();
        if (!$room) {
            $room = Room::create([
                'name' => 'Studio 1',
                'capacity' => 20,
                'location' => 'Main Building',
                'is_active' => true,
            ]);
            echo "Created default room: Studio 1" . PHP_EOL;
        }

        echo PHP_EOL . "Creating Batch Classes..." . PHP_EOL;

        // Create Batch Classes for the next 30 days
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(30);
        
        $timeSlots = [
            ['06:00', '07:00'],
            ['07:30', '08:30'],
            ['09:00', '10:00'],
            ['10:30', '11:30'],
            ['16:00', '17:00'],
            ['17:30', '18:30'],
            ['19:00', '20:00'],
        ];

        $batchClassCount = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip Sundays (optional)
            if ($date->dayOfWeek === Carbon::SUNDAY) {
                continue;
            }

            foreach ($timeSlots as $slot) {
                // Randomly assign Yoga or Mat Pilates
                $isYoga = rand(0, 10) <= 7; // 70% Yoga, 30% Mat Pilates
                
                if ($isYoga) {
                    $masterClass = $yoga;
                    $instructor = $yogaInstructorModels[array_rand($yogaInstructorModels)];
                } else {
                    $masterClass = $matPilates;
                    $instructor = $mimiInstructor;
                }

                $startTime = $slot[0];
                $endTime = $slot[1];

                BatchClass::create([
                    'master_class_id' => $masterClass->id,
                    'instructor_id' => $instructor->id,
                    'room_id' => $room->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'price' => $isYoga ? 150000 : 175000, // Yoga: 150k, Mat Pilates: 175k
                    'capacity' => 15,
                    'remaining_slots' => 15,
                    'status' => 'active',
                    'visibility' => 'public',
                ]);

                $batchClassCount++;
            }
        }

        echo "Created {$batchClassCount} Batch Classes" . PHP_EOL;
        echo PHP_EOL . "=== SEEDING COMPLETED ===" . PHP_EOL;
        echo "Master Classes: 2 (Yoga, Mat Pilates)" . PHP_EOL;
        echo "Instructors: 11 (10 Yoga + 1 Mat Pilates)" . PHP_EOL;
        echo "Batch Classes: {$batchClassCount}" . PHP_EOL;
    }
}

