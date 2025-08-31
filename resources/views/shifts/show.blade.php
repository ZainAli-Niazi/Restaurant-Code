@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shift Details</h1>

    <p><strong>Name:</strong> {{ $shift->name }}</p>
    <p><strong>User:</strong> {{ $shift->user->name ?? 'N/A' }}</p>
    <p><strong>Starting Cash:</strong> {{ $shift->starting_cash }}</p>
    <p><strong>Ending Cash:</strong> {{ $shift->ending_cash ?? '-' }}</p>
    <p><strong>Start Time:</strong> {{ $shift->start_time }}</p>
    <p><strong>End Time:</strong> {{ $shift->end_time ?? '-' }}</p>
    <p><strong>Notes:</strong> {{ $shift->notes ?? '-' }}</p>
</div>
@endsection
