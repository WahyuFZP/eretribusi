@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit User</h1>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 max-w-xl">
                <label class="block">
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="input input-bordered w-full" required>
                </label>

                <label class="block">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="input input-bordered w-full" required>
                </label>

                <label class="block">
                    <span>Password (leave empty to keep current)</span>
                    <input type="password" name="password" class="input input-bordered w-full">
                </label>

                <label class="block">
                    <span>Confirm Password</span>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full">
                </label>

                <label class="block">
                    <span>Role</span>
                    <select name="role" class="select select-bordered w-full">
                        <option value="">-- Choose role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-error" role="button">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
        
    </div>
@endsection
