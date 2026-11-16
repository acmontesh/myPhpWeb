@extends('admin.layouts.dashboard')

@section('title', 'Edit Blog Post')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/jodit/jodit.min.css') }}">
    <script src="{{ asset('vendor/jodit/jodit.min.js') }}" defer></script>
@endpush

@section('content')
    @include('admin.blog._form')
@endsection
