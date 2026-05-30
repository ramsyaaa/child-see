@extends('superadmin.layout.master')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $title ?? 'Module' }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $title ?? 'Module' }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-tools" style="font-size: 4rem; color: #FF6F51;"></i>
                </div>
                <h3 class="mb-3" style="color: #FF6F51;">{{ $message ?? 'This module is coming soon!' }}</h3>
                <p class="text-muted mb-4">
                    We're working hard to bring you this feature. Stay tuned for updates!
                </p>
                <a href="{{ route('superadmin.dashboard') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

