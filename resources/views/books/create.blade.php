@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Új könyv hozzáadása</span>
                <a href="{{ route('books.index', $author_id) }}" class="btn btn-secondary btn-sm">Vissza</a>
            </div>

            <div class="card-body">
                <form action="{{ route('books.store', $author_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Könyv címe *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Ár (Ft) *</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" required>
                             @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="iban" class="form-label">IBAN *</label>
                            <input type="text" name="iban" class="form-control @error('iban') is-invalid @enderror" 
                                   value="{{ old('iban') }}" required>
                             @error('iban')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="genre" class="form-label">Műfaj</label>
                        <input type="text" name="genre" class="form-control" value="{{ old('genre') }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Leírás</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_path" class="form-label">Borítókép (opcionális)</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Mentés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection