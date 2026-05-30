@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Calendar</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Class Calendar</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $startDate->format('F Y') }}</h5>
                <div>
                    <a href="{{ route('superadmin.calendar', ['month' => $startDate->copy()->subMonth()->month, 'year' => $startDate->copy()->subMonth()->year]) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <a href="{{ route('superadmin.calendar') }}" class="btn btn-sm btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                        Today
                    </a>
                    <a href="{{ route('superadmin.calendar', ['month' => $startDate->copy()->addMonth()->month, 'year' => $startDate->copy()->addMonth()->year]) }}" class="btn btn-sm btn-secondary">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sunday</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentDate = $startDate->copy()->startOfWeek();
                                $endOfMonth = $endDate->copy()->endOfWeek();
                            @endphp
                            
                            @while($currentDate <= $endOfMonth)
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        <td class="align-top calendar-cell" style="height: 120px; {{ $currentDate->month != $month ? 'background-color: #f8f9fa;' : '' }} cursor: pointer; position: relative;"
                                            data-date="{{ $currentDate->format('Y-m-d') }}"
                                            onclick="window.location.href='{{ route('superadmin.batch-classes.create', ['date' => $currentDate->format('Y-m-d')]) }}'">
                                            <div class="fw-bold mb-2">{{ $currentDate->day }}</div>
                                            @php
                                                $dateKey = $currentDate->format('Y-m-d');
                                                $classes = $classesByDate->get($dateKey, collect());
                                            @endphp

                                            @foreach($classes as $class)
                                                @php
                                                    // Use master class color if available, otherwise use default based on category
                                                    $eventColor = $class->masterClass->color ?? '#FF6F51';
                                                    // Calculate text color based on background brightness
                                                    $r = hexdec(substr($eventColor, 1, 2));
                                                    $g = hexdec(substr($eventColor, 3, 2));
                                                    $b = hexdec(substr($eventColor, 5, 2));
                                                    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                                                    $textColor = $brightness > 155 ? '#000000' : '#FFFFFF';
                                                @endphp
                                                <div class="mb-1" onclick="event.stopPropagation(); window.location.href='{{ route('superadmin.batch-classes.show', $class->id) }}'">
                                                    <small class="badge d-block text-start" style="background-color: {{ $eventColor }}; color: {{ $textColor }}; font-size: 0.7rem; cursor: pointer;">
                                                        {{ date('H:i', strtotime($class->start_time)) }} - {{ $class->masterClass->name }}
                                                    </small>
                                                </div>
                                            @endforeach

                                            @if($currentDate->month == $month)
                                                <div class="position-absolute bottom-0 end-0 p-1">
                                                    <small class="text-muted" style="font-size: 0.65rem;">
                                                        <i class="fas fa-plus-circle"></i> Add
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        @php $currentDate->addDay(); @endphp
                                    @endfor
                                </tr>
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .calendar-cell {
        transition: all 0.2s ease;
    }

    .calendar-cell:hover {
        background-color: #fff6f4 !important;
        box-shadow: inset 0 0 0 2px #FF6F51;
    }

    .calendar-cell .badge:hover {
        opacity: 0.85;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    .calendar-cell .position-absolute {
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .calendar-cell:hover .position-absolute {
        opacity: 1;
    }
</style>
@endpush

