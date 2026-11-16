@extends('admin.layouts.dashboard')

@section('title', 'New Research Project')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/jodit/jodit.min.css') }}">
    <script src="{{ asset('vendor/jodit/jodit.min.js') }}" defer></script>
@endpush

@section('content')
    @include('admin.research._form')
@endsection
