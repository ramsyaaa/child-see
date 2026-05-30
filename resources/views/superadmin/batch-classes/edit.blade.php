@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.batch-classes.index') }}">Batch Classes</a></li>
                    <li class="breadcrumb-item" aria-current="page">Edit</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Edit Class</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Class Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.batch-classes.update', $batchClass) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="master_class_id" class="form-label">Master Class <span class="text-danger">*</span></label>
                            <select class="form-select @error('master_class_id') is-invalid @enderror" id="master_class_id" name="master_class_id" required>
                                <option value="">Select Master Class</option>
                                @foreach($masterClasses as $masterClass)
                                    <option value="{{ $masterClass->id }}" {{ old('master_class_id', $batchClass->master_class_id) == $masterClass->id ? 'selected' : '' }}>
                                        {{ $masterClass->name }} ({{ $masterClass->default_duration }} min)
                                    </option>
                                @endforeach
                            </select>
                            @error('master_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                            <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                                <option value="">Select Instructor</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id', $batchClass->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                            <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', $batchClass->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_name }} (Capacity: {{ $room->capacity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $batchClass->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', date('H:i', strtotime($batchClass->start_time))) }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', date('H:i', strtotime($batchClass->end_time))) }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Drop-in Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $batchClass->price) }}" min="0" step="1000" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $batchClass->capacity) }}" min="1" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $batchClass->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ old('status', $batchClass->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                        <select class="form-select @error('visibility') is-invalid @enderror" id="visibility" name="visibility" required>
                            <option value="public" {{ old('visibility', $batchClass->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility', $batchClass->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                        @error('visibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Private classes are only visible to members with subscriptions</small>
                    </div>

                    <!-- Gender Restriction -->
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="is_have_gender_restriction" name="is_have_gender_restriction" value="1" {{ old('is_have_gender_restriction', $batchClass->is_have_gender_restriction) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_have_gender_restriction">
                                Enable Gender Restriction
                            </label>
                        </div>
                        <div id="gender_restriction_field" style="display: {{ old('is_have_gender_restriction', $batchClass->is_have_gender_restriction) ? 'block' : 'none' }};">
                            <select class="form-select @error('gender_restriction') is-invalid @enderror" id="gender_restriction" name="gender_restriction">
                                <option value="">Select Gender Restriction</option>
                                <option value="Women" {{ old('gender_restriction', $batchClass->gender_restriction) == 'Women' ? 'selected' : '' }}>Women Only</option>
                                <option value="All Gender" {{ old('gender_restriction', $batchClass->gender_restriction) == 'All Gender' ? 'selected' : '' }}>All Gender</option>
                                <option value="Men" {{ old('gender_restriction', $batchClass->gender_restriction) == 'Men' ? 'selected' : '' }}>Men Only</option>
                            </select>
                            @error('gender_restriction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Age Restriction -->
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="is_have_age_restriction" name="is_have_age_restriction" value="1" {{ old('is_have_age_restriction', $batchClass->is_have_age_restriction) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_have_age_restriction">
                                Enable Age Restriction
                            </label>
                        </div>
                        <div id="age_restriction_field" style="display: {{ old('is_have_age_restriction', $batchClass->is_have_age_restriction) ? 'block' : 'none' }};">
                            <input type="text" class="form-control @error('age_restriction') is-invalid @enderror" id="age_restriction" name="age_restriction" value="{{ old('age_restriction', $batchClass->age_restriction) }}" placeholder="e.g., 18+, 13-17, All Ages">
                            @error('age_restriction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Specify age requirement (e.g., "18+", "13-17", "All Ages")</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.batch-classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                            <i class="fas fa-save me-2"></i>Update Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle gender restriction field
    $('#is_have_gender_restriction').on('change', function() {
        if ($(this).is(':checked')) {
            $('#gender_restriction_field').slideDown();
        } else {
            $('#gender_restriction_field').slideUp();
            $('#gender_restriction').val('');
        }
    });

    // Toggle age restriction field
    $('#is_have_age_restriction').on('change', function() {
        if ($(this).is(':checked')) {
            $('#age_restriction_field').slideDown();
        } else {
            $('#age_restriction_field').slideUp();
            $('#age_restriction').val('');
        }
    });
});
</script>
@endsection

