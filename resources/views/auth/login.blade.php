@extends('layouts.master')


@section('title', 'Login | E-commerce')

@section('auth-content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow rounded">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter your email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    @if(session('status'))
                        <div class="alert alert-danger mt-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-success btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection










