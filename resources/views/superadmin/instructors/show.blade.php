@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.instructors.index') }}">Instructors</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $instructor->name }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $instructor->name }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Instructor Details</h5>
                <div>
                    <a href="{{ route('superadmin.instructors.edit', $instructor) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('superadmin.instructors.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($instructor->photo_url)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $instructor->photo_url) }}" alt="{{ $instructor->name }}" class="rounded-circle" width="150" height="150">
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Name:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $instructor->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Specialization:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $instructor->specialization ?? 'N/A' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Bio:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $instructor->bio ?? 'No bio provided' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($instructor->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Created At:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $instructor->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Last Updated:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $instructor->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Classes:</span>
                        <strong class="text-primary">{{ $instructor->batchClasses->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Upcoming Classes:</span>
                        <strong class="text-success">{{ $instructor->batchClasses()->where('class_date', '>=', now())->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

