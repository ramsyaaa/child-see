<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'room_name' => 'Studio A - Main Hall',
                'capacity' => 30,
                'facilities' => 'Large mirrors, sound system, yoga mats, blocks, straps, air conditioning, wooden floor',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio B - Yoga Room',
                'capacity' => 20,
                'facilities' => 'Mirrors, ambient lighting, yoga mats, bolsters, blankets, meditation cushions, air conditioning',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio C - Pilates Room',
                'capacity' => 12,
                'facilities' => '6 Reformer machines, Pilates chairs, barrels, resistance bands, mirrors, air conditioning',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio D - Cycling Room',
                'capacity' => 25,
                'facilities' => '25 stationary bikes, sound system, LED lighting, fans, water dispensers, towel service',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio E - Boxing Arena',
                'capacity' => 15,
                'facilities' => 'Boxing bags, speed bags, boxing ring, gloves, hand wraps, mirrors, rubber flooring',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio F - Dance Studio',
                'capacity' => 25,
                'facilities' => 'Full-wall mirrors, professional sound system, sprung floor, ballet barre, air conditioning',
                'is_active' => true,
            ],
            [
                'room_name' => 'Studio G - Meditation Room',
                'capacity' => 15,
                'facilities' => 'Meditation cushions, yoga mats, ambient lighting, sound system, aromatherapy diffuser, quiet environment',
                'is_active' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['room_name' => $room['room_name']], $room);
        }
    }
}

