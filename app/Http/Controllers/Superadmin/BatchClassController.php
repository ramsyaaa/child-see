<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BatchClass;
use App\Models\MasterClass;
use App\Models\Instructor;
use App\Models\Room;
use Illuminate\Http\Request;

class BatchClassController extends Controller
{
    public function index()
    {
        $batchClasses = BatchClass::with(['masterClass', 'instructor', 'room'])
            ->latest('date')
            ->latest('start_time')
            ->paginate(15);
        
        return view('superadmin.batch-classes.index', compact('batchClasses'));
    }

    public function create(Request $request)
    {
        $masterClasses = MasterClass::active()->get();
        $instructors = Instructor::active()->get();
        $rooms = Room::active()->get();

        // Get the date from query parameter if provided
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        return view('superadmin.batch-classes.create', compact('masterClasses', 'instructors', 'rooms', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'master_class_id' => 'required|exists:master_classes,id',
            'instructor_id' => 'required|exists:instructors,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,cancelled',
            'visibility' => 'required|in:public,private',
            'is_have_gender_restriction' => 'boolean',
            'gender_restriction' => 'nullable|in:Women,All Gender,Men',
            'is_have_age_restriction' => 'boolean',
            'age_restriction' => 'nullable|string|max:100',
        ]);

        // Handle checkboxes (unchecked checkboxes are not sent in the request)
        $validated['is_have_gender_restriction'] = $request->has('is_have_gender_restriction');
        $validated['is_have_age_restriction'] = $request->has('is_have_age_restriction');

        // Clear restriction values if not enabled
        if (!$validated['is_have_gender_restriction']) {
            $validated['gender_restriction'] = null;
        }
        if (!$validated['is_have_age_restriction']) {
            $validated['age_restriction'] = null;
        }

        $validated['remaining_slots'] = $validated['capacity'];

        BatchClass::create($validated);

        return redirect()->route('superadmin.batch-classes.index')
            ->with('success', 'Batch Class created successfully!');
    }

    public function show(BatchClass $batchClass)
    {
        $batchClass->load(['masterClass', 'instructor', 'room', 'bookings.user']);
        return view('superadmin.batch-classes.show', compact('batchClass'));
    }

    public function edit(BatchClass $batchClass)
    {
        $masterClasses = MasterClass::active()->get();
        $instructors = Instructor::active()->get();
        $rooms = Room::active()->get();
        
        return view('superadmin.batch-classes.edit', compact('batchClass', 'masterClasses', 'instructors', 'rooms'));
    }

    public function update(Request $request, BatchClass $batchClass)
    {
        $validated = $request->validate([
            'master_class_id' => 'required|exists:master_classes,id',
            'instructor_id' => 'required|exists:instructors,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,cancelled',
            'visibility' => 'required|in:public,private',
            'is_have_gender_restriction' => 'boolean',
            'gender_restriction' => 'nullable|in:Women,All Gender,Men',
            'is_have_age_restriction' => 'boolean',
            'age_restriction' => 'nullable|string|max:100',
        ]);

        // Handle checkboxes (unchecked checkboxes are not sent in the request)
        $validated['is_have_gender_restriction'] = $request->has('is_have_gender_restriction');
        $validated['is_have_age_restriction'] = $request->has('is_have_age_restriction');

        // Clear restriction values if not enabled
        if (!$validated['is_have_gender_restriction']) {
            $validated['gender_restriction'] = null;
        }
        if (!$validated['is_have_age_restriction']) {
            $validated['age_restriction'] = null;
        }

        // Adjust remaining slots if capacity changed
        if ($validated['capacity'] != $batchClass->capacity) {
            $bookedSlots = $batchClass->capacity - $batchClass->remaining_slots;
            $validated['remaining_slots'] = $validated['capacity'] - $bookedSlots;
        }

        $batchClass->update($validated);

        return redirect()->route('superadmin.batch-classes.index')
            ->with('success', 'Batch Class updated successfully!');
    }

    public function destroy(BatchClass $batchClass)
    {
        if ($batchClass->bookings()->count() > 0) {
            return redirect()->route('superadmin.batch-classes.index')
                ->with('error', 'Cannot delete Batch Class that has bookings!');
        }

        $batchClass->delete();

        return redirect()->route('superadmin.batch-classes.index')
            ->with('success', 'Batch Class deleted successfully!');
    }
}

