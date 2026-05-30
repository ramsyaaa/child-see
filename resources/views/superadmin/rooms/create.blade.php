@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.rooms.index') }}">Rooms</a></li>
                    <li class="breadcrumb-item" aria-current="page">Create</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Create Room</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Room Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.rooms.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="room_name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('room_name') is-invalid @enderror" id="room_name" name="room_name" value="{{ old('room_name') }}" required autofocus>
                        @error('room_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum number of people</small>
                    </div>

                    <div class="mb-3">
                        <label for="facilities" class="form-label">Facilities</label>
                        <textarea class="form-control @error('facilities') is-invalid @enderror" id="facilities" name="facilities" rows="4" placeholder="e.g., Mirrors, Sound System, Air Conditioning">{{ old('facilities') }}</textarea>
                        @error('facilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <small class="text-muted">Inactive rooms won't be available for scheduling</small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                            <i class="fas fa-save me-2"></i>Create Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

