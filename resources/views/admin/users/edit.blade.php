@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit User: {{ $user->name }}</h3>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="candidate" {{ $user->role == 'candidate' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="col-md-12">
                <hr>
                <h5>Change Password (leave blank to keep current)</h5>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="********">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="********">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection