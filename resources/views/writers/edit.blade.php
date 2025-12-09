@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Szerző szerkesztése: {{ $writer['name'] }}</span>
                <a href="{{ route('writers.index') }}" class="btn btn-secondary btn-sm">Vissza</a>
            </div>
            
            <div class="card-body">
                <form action="{{ route('writers.update', $writer['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label for="name" class="form-label">Szerző neve *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $writer['name']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Életrajz</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4">{{ old('bio', $writer['bio'] ?? '') }}</textarea>
                    </div>

                    @if(isset($writer['portrait_path']))
                        <div class="mb-2">
                            <p>Jelenlegi kép:</p>
                            <img src="http://localhost:8000/storage/{{ $writer['portrait_path'] }}" height="100">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="portrait_path" class="form-label">Új portré (opcionális)</label>
                        <input class="form-control" type="file" id="portrait_path" name="portrait_path">
                        <div class="form-text">Csak akkor tölts fel, ha cserélni szeretnéd.</div>
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