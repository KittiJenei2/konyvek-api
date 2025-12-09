@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Bejelentkezés</div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf <div class="mb-3">
                        <label for="email" class="form-label">Email cím</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Jelszó</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Belépés</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection