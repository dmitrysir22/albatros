@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h3>Job Listings Management</h3>
        <a href="{{ route('admin.vacancies.create') }}" class="btn btn-primary">Add New Job</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title / Company</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vacancies as $vacancy)
            <tr>
                <td>
                    <strong>{{ $vacancy->title }}</strong><br>
                    <small class="text-muted">{{ $vacancy->company_name }}</small>
                </td>
                <td>{{ $vacancy->type }}</td>
                <td>
                    <span class="badge {{ $vacancy->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $vacancy->is_active ? 'Active' : 'Draft' }}
                    </span>
                </td>
                <td>{{ $vacancy->created_at->format('d.m.Y') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.vacancies.edit', $vacancy->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                        <form action="{{ route('admin.vacancies.destroy', $vacancy->id) }}" method="POST" onsubmit="return confirm('Delete vacancy?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $vacancies->links() }}
</div>
@endsection