@extends('layouts.app')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-3">
                <h5 class="card-title mb-0 fw-bold">Login Admin Toko PPOB</h5>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('login.perform') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required placeholder="******">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Masuk / Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
