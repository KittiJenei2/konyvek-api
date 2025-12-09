@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Szerzők</h1>
    <div>
        <a href="{{ route('writers.index', ['export' => 'csv']) }}" class="btn btn-outline-success btn-sm">CSV Export</a>
        <a href="{{ route('writers.index', ['export' => 'pdf']) }}" class="btn btn-outline-danger btn-sm">PDF Export</a>
        @if(Session::has('api_token'))
            <a href="{{ route('writers.create') }}" class="btn btn-primary btn-sm ms-2">Új szerző</a>
        @endif
    </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Név</th>
            <th>Biográfia</th>
            <th>Könyvek</th>
            @if(Session::has('api_token'))
                <th>Műveletek</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($writers as $writer)
            <tr>
                <td>{{ $writer['name'] }}</td>
                <td>{{ Str::limit($writer['bio'], 50) }}</td>
                <td>
                    <a href="{{ url('/writers/' . $writer['id'] . '/books') }}" class="btn btn-info btn-sm text-white">
                        Könyvek listája
                    </a>
                </td>
                
                @if(Session::has('api_token'))
                    <td>
                        <a href="{{ route('writers.edit', $writer['id']) }}" class="btn btn-warning btn-sm">Szerk.</a>
                        
                        <form action="{{ url('/writers/' . $writer['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törlöd?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Törlés</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Nincsenek megjeleníthető szerzők.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection