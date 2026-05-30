<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(10);
        return view('superadmin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('superadmin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Room::create($validated);

        return redirect()->route('superadmin.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    public function show(Room $room)
    {
        $room->load('batchClasses');
        return view('superadmin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('superadmin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $room->update($validated);

        return redirect()->route('superadmin.rooms.index')
            ->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        if ($room->batchClasses()->count() > 0) {
            return redirect()->route('superadmin.rooms.index')
                ->with('error', 'Cannot delete Room that has scheduled batch classes!');
        }

        $room->delete();

        return redirect()->route('superadmin.rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}

