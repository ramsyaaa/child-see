@extends('superadmin.layout.master')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.master-classes.index') }}">Master Classes</a></li>
                    <li class="breadcrumb-item" aria-current="page">Edit</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Edit Master Class</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Master Class Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.master-classes.update', $masterClass) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $masterClass->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $masterClass->category) }}" placeholder="e.g., Yoga, Pilates, Zumba" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Describe the class...">{{ old('description', $masterClass->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                            <select class="form-select @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                <option value="">Select Difficulty</option>
                                <option value="beginner" {{ old('difficulty_level', $masterClass->difficulty_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty_level', $masterClass->difficulty_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty_level', $masterClass->difficulty_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            @error('difficulty_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="default_duration" class="form-label">Default Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('default_duration') is-invalid @enderror" id="default_duration" name="default_duration" value="{{ old('default_duration', $masterClass->default_duration) }}" min="15" max="180" required>
                            @error('default_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Calendar Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                                    id="color" name="color" value="{{ old('color', $masterClass->color ?? '#FF6F51') }}"
                                    title="Choose a color for calendar display">
                                <input type="text" class="form-control @error('color') is-invalid @enderror"
                                    id="color_text" value="{{ old('color', $masterClass->color ?? '#FF6F51') }}"
                                    placeholder="#FF6F51" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <small class="text-muted">This color will be used to display events in the calendar</small>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $masterClass->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <small class="text-muted">Inactive classes won't be available for scheduling</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.master-classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                            <i class="fas fa-save me-2"></i>Update Master Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Sync color picker with text input
    $('#color').on('input', function() {
        $('#color_text').val($(this).val().toUpperCase());
    });

    $('#color_text').on('input', function() {
        let value = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            $('#color').val(value);
        }
    });

    // Ensure color value is synced before form submission
    $('form').on('submit', function() {
        let textValue = $('#color_text').val();
        if (textValue && /^#[0-9A-Fa-f]{6}$/.test(textValue)) {
            $('#color').val(textValue);
        }
    });
});
</script>
@endsection

