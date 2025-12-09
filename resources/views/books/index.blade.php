@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Könyvek listája</h1>
    <a href="{{ route('writers.index') }}" class="btn btn-secondary">Vissza a szerzőkhöz</a>
</div>

@if(Session::has('api_token'))
    <div class="mb-3">
        <a href="{{ route('books.create', $author_id) }}" class="btn btn-primary">Új könyv hozzáadása</a>
    </div>
@endif

<div class="row">
    @forelse($books as $book)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if(isset($book['image_path']))
                    <img src="http://localhost:8000/storage/{{ $book['image_path'] }}" class="card-img-top" alt="Borító" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span>Nincs kép</span>
                    </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $book['title'] }}</h5>
                    <p class="card-text text-muted">{{ $book['price'] }} Ft</p>
                    <p class="card-text">{{ Str::limit($book['description'], 100) }}</p>
                </div>
                
                @if(Session::has('api_token'))
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="{{ route('books.edit', ['author_id' => $author_id, 'id' => $book['id']]) }}" class="btn btn-warning btn-sm">Szerkesztés</a>
                        <form action="{{ url('/writers/' . $author_id . '/books/' . $book['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Biztosan?')">Törlés</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Ehhez a szerzőhöz még nincsenek könyvek feltöltve.</div>
        </div>
    @endforelse
</div>
@endsection