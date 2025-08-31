@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
 

    <form method="POST" action="{{ route('shifts.store') }}">
        @csrf
        <div class="mb-3">
            <label>Shift Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Starting Cash</label>
            <input type="number" name="starting_cash" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Start Shift</button>
    </form>
</div>
@endsection
