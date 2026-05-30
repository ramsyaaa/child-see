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
                    <li class="breadcrumb-item" aria-current="page">{{ $masterClass->name }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $masterClass->name }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Class Details</h5>
                <div>
                    <a href="{{ route('superadmin.master-classes.edit', $masterClass) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('superadmin.master-classes.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Class Name:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $masterClass->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Category:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-light-secondary">{{ $masterClass->category }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $masterClass->description ?? 'No description provided' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Difficulty Level:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($masterClass->difficulty_level == 'beginner')
                            <span class="badge bg-success">Beginner</span>
                        @elseif($masterClass->difficulty_level == 'intermediate')
                            <span class="badge bg-warning">Intermediate</span>
                        @else
                            <span class="badge bg-danger">Advanced</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Default Duration:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $masterClass->default_duration }} minutes
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($masterClass->is_active)
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
                        {{ $masterClass->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Last Updated:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $masterClass->updated_at->format('d M Y, H:i') }}
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
                        <span>Total Batch Classes:</span>
                        <strong class="text-primary">{{ $masterClass->batchClasses->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Upcoming Classes:</span>
                        <strong class="text-success">{{ $masterClass->batchClasses()->where('class_date', '>=', now())->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

