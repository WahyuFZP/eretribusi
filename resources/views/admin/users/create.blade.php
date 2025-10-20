@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4 max-w-xl">
            <label class="block">
                <span>Name</span>
                <input type="text" name="name" class="input input-bordered w-full" required>
            </label>

            <label class="block">
                <span>Email</span>
                <input type="email" name="email" class="input input-bordered w-full" required>
            </label>

            <label class="block">
                <span>Password</span>
                <input type="password" name="password" class="input input-bordered w-full" required>
            </label>

            <label class="block">
                <span>Confirm Password</span>
                <input type="password" name="password_confirmation" class="input input-bordered w-full" required>
            </label>

            <label class="block">
                <span>Role</span>
                <select name="role" class="select select-bordered w-full">
                    <option value="">-- Choose role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </label>

            <div>
                <button class="btn btn-primary">Create</button>
            </div>
        </div>
    </form>
</div>
@endsection
