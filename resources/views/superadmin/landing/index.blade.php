@extends('superadmin.layout.master')
@section('title', 'Konten Landing Page')
@section('content')
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Konten Landing Page</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Landing Content</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row">
  @php
    $sections = [
      ['label'=>'Fakta Unik','count'=>$factCount,'icon'=>'ti ti-bulb','route'=>'superadmin.landing.facts','color'=>'#8E77AB'],
      ['label'=>'Tim Pengembang','count'=>$teamCount,'icon'=>'ti ti-users','route'=>'superadmin.landing.team','color'=>'#8499B6'],
      ['label'=>'HKI / Paten','count'=>$hkiCount,'icon'=>'ti ti-certificate','route'=>'superadmin.landing.hki','color'=>'#B9A5D6'],
      ['label'=>'Partner','count'=>$partnerCount,'icon'=>'ti ti-building','route'=>'superadmin.landing.partners','color'=>'#C6D9E8'],
    ];
  @endphp
  @foreach($sections as $s)
  <div class="col-md-3 col-sm-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s" style="background:{{ $s['color'] }}22;">
              <i class="{{ $s['icon'] }} f-24" style="color:{{ $s['color'] }};"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">{{ $s['label'] }}</h6>
          </div>
        </div>
        <h3 class="mb-1">{{ $s['count'] }}</h3>
        <p class="text-muted mb-0">item terdaftar</p>
        <a href="{{ route($s['route']) }}" class="btn btn-sm mt-3" style="background:{{ $s['color'] }};color:#fff;">Kelola</a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
