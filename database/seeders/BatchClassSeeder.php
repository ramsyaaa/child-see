<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BatchClass;
use App\Models\MasterClass;
use App\Models\Instructor;
use App\Models\Room;
use Carbon\Carbon;

class BatchClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active master classes, instructors, and rooms
        $masterClasses = MasterClass::where('is_active', true)->get();
        $instructors = Instructor::where('is_active', true)->get();
        $rooms = Room::where('is_active', true)->get();

        if ($masterClasses->isEmpty() || $instructors->isEmpty() || $rooms->isEmpty()) {
            $this->command->warn('Please run MasterClassSeeder, InstructorSeeder, and RoomSeeder first!');
            return;
        }

        // Define time slots for classes
        $timeSlots = [
            ['06:00:00', '07:00:00'], // Early morning
            ['07:30:00', '08:30:00'], // Morning
            ['09:00:00', '10:00:00'], // Mid morning
            ['10:30:00', '11:30:00'], // Late morning
            ['12:00:00', '13:00:00'], // Lunch time
            ['17:00:00', '18:00:00'], // Evening
            ['18:30:00', '19:30:00'], // Late evening
            ['19:45:00', '20:45:00'], // Night
        ];

        $batchClasses = [];
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(2)->endOfMonth();

        // Generate classes for the next 3 months
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Skip if it's a past date
            if ($currentDate->isPast() && !$currentDate->isToday()) {
                $currentDate->addDay();
                continue;
            }

            // Create 3-5 random classes per day
            $classesPerDay = rand(3, 5);
            
            for ($i = 0; $i < $classesPerDay; $i++) {
                $masterClass = $masterClasses->random();
                $instructor = $instructors->random();
                $room = $rooms->random();
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                
                // Determine status based on date
                $status = 'active';

                // Random chance of cancelled status (5%)
                if (rand(1, 100) <= 5 && $currentDate->isFuture()) {
                    $status = 'cancelled';
                }

                // Calculate remaining slots (random between 0 and capacity)
                $remainingSlots = $currentDate->isPast()
                    ? rand(0, (int)($room->capacity * 0.3)) // Past classes have fewer remaining slots
                    : rand((int)($room->capacity * 0.5), $room->capacity); // Future classes have more slots

                // Random price between 75000 and 150000
                $price = rand(75, 150) * 1000;

                $batchClasses[] = [
                    'master_class_id' => $masterClass->id,
                    'instructor_id' => $instructor->id,
                    'room_id' => $room->id,
                    'date' => $currentDate->format('Y-m-d'),
                    'start_time' => $timeSlot[0],
                    'end_time' => $timeSlot[1],
                    'price' => $price,
                    'capacity' => $room->capacity,
                    'remaining_slots' => $remainingSlots,
                    'status' => $status,
                    'visibility' => 'public',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $currentDate->addDay();
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($batchClasses, 50) as $chunk) {
            BatchClass::insert($chunk);
        }

        $this->command->info('Created ' . count($batchClasses) . ' batch classes!');
    }
}

