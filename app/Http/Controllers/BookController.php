<?php

namespace App\Http\Controllers;

use App\Services\ApiService; // 1. FONTOS: Be kell húzni a Service-t
use Illuminate\Http\Request;

class BookController extends Controller
{
    // 2. FONTOS: Deklarálni kell a változót
    protected $api;

    // 3. FONTOS: A konstruktorban kérjük el a Laraveltől a Service-t
    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    public function index($author_id)
    {
        // 1. Összeállítjuk az URL-t (pl. /writers/1/books)
        $endpoint = "/writers/{$author_id}/books";
        
        // 2. API hívás
        $response = $this->api->get($endpoint);
        
        // 3. Adatok kinyerése a 'books' kulcsból, ha sikeres
        if ($response->successful()) {
            $books = $response->json('books');
            // Biztosítjuk, hogy tömb legyen (ha esetleg null-t kapnánk)
            $books = is_array($books) ? $books : []; 
        } else {
            // Hiba esetén üres tömb és hibaüzenet
            $books = [];
            return back()->withErrors([
                'api_error' => 'Hiba történt a könyvek lekérdezésekor. Ellenőrizze az API futását és a paramétert.'
            ]);
        }
        
        // 4. Átadjuk a nézetnek
        return view('books.index', compact('books', 'author_id'));
    }

    public function store(Request $request, $author_id)
    {
        // Könyv létrehozása az adott szerzőhöz
        $this->api->post("/writers/{$author_id}/books", $request->all());
        
        return back();
    }
}