@extends('admin.layouts.dashboard')

@section('title', 'Sections')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Turn public sections on or off, and set the order they appear in navigation.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.sections.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Order</th>
                        <th>Enabled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr>
                            <td>{{ $section->label }}</td>
                            <td>
                                <input
                                    type="number"
                                    name="sections[{{ $section->id }}][display_order]"
                                    value="{{ old("sections.{$section->id}.display_order", $section->display_order) }}"
                                    min="0"
                                    class="data-table__order-input"
                                >
                            </td>
                            <td>
                                <label class="form-check">
                                    <input
                                        type="checkbox"
                                        name="sections[{{ $section->id }}][enabled]"
                                        value="1"
                                        {{ old("sections.{$section->id}.enabled", $section->enabled) ? 'checked' : '' }}
                                    >
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn--inline btn--spaced">Save sections</button>
    </form>
@endsection
