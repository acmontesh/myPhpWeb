@extends('admin.layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-welcome">
        <h1>Welcome, {{ auth('admin')->user()->name }}</h1>
        <p>Here's a quick way to jump into the things you'll do most.</p>
    </div>

    <div class="quick-actions">
        <a href="{{ route('admin.blog.create') }}" class="quick-action-card">
            <span class="quick-action-card__icon"><i class="fa-solid fa-pen-nib" aria-hidden="true"></i></span>
            <span>
                <span class="quick-action-card__label">New Blog Post</span>
                <p class="quick-action-card__hint">Write and publish an article</p>
            </span>
        </a>

        <a href="{{ route('admin.research.create') }}" class="quick-action-card">
            <span class="quick-action-card__icon"><i class="fa-solid fa-flask" aria-hidden="true"></i></span>
            <span>
                <span class="quick-action-card__label">New Research Project</span>
                <p class="quick-action-card__hint">Add a project to the research page</p>
            </span>
        </a>

        <a href="{{ route('admin.publications.create') }}" class="quick-action-card">
            <span class="quick-action-card__icon"><i class="fa-solid fa-book" aria-hidden="true"></i></span>
            <span>
                <span class="quick-action-card__label">New Publication</span>
                <p class="quick-action-card__hint">Add a journal or conference paper</p>
            </span>
        </a>

        <a href="{{ route('admin.quotes.create') }}" class="quick-action-card">
            <span class="quick-action-card__icon"><i class="fa-solid fa-quote-left" aria-hidden="true"></i></span>
            <span>
                <span class="quick-action-card__label">New Quote</span>
                <p class="quick-action-card__hint">Add a quote to the carousel</p>
            </span>
        </a>
    </div>
@endsection
