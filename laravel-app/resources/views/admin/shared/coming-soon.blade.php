@extends('admin.layouts.dashboard')

@section('title', $title)

@section('content')
    <div class="coming-soon">
        <i class="{{ $icon }}" aria-hidden="true"></i>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>
    </div>
@endsection
