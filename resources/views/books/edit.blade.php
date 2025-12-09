@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Könyv szerkesztése: {{ $book['title'] }}</span>
                <a href="{{ route('books.index', $author_id) }}" class="btn btn-secondary btn-sm">Vissza</a>
            </div>

            <div class="card-body">
                <form action="{{ route('books.update', ['author_id' => $author_id, 'id' => $book['id']]) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Cím *</label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ old('title', $book['title']) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Ár (Ft) *</label>
                            <input type="number" name="price" class="form-control" 
                                   value="{{ old('price', $book['price']) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="iban" class="form-label">IBAN *</label>
                            <input type="text" name="iban" class="form-control" 
                                   value="{{ old('iban', $book['iban']) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="genre" class="form-label">Műfaj</label>
                        <input type="text" name="genre" class="form-control" 
                               value="{{ old('genre', $book['genre'] ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Leírás</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $book['description'] ?? '') }}</textarea>
                    </div>

                    @if(isset($book['image_path']))
                        <div class="mb-2">
                            <p>Jelenlegi borító:</p>
                            <img src="http://localhost:8000/storage/{{ $book['image_path'] }}" height="100">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="image_path" class="form-label">Új borítókép (opcionális)</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Módosítások mentése</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection