@extends('admin.layouts.dashboard')

@section('title', 'New News Item')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/jodit/jodit.min.css') }}">
    <script src="{{ asset('vendor/jodit/jodit.min.js') }}" defer></script>
@endpush

@section('content')
    @include('admin.news._form')
@endsection
