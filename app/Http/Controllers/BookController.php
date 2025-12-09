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
        // Lekérjük a könyveket az adott szerzőhöz
        $response = $this->api->get("/writers/{$author_id}/books");
        
        // Ellenőrizzük, hogy sikeres-e a kérés, mielőtt lekérjük a JSON-t
        // Ha nem, üres tömböt adunk vissza, hogy ne omoljon össze a nézet
        $books = $response->successful() ? $response->json() : [];
        
        return view('books.index', compact('books', 'author_id'));
    }

    public function store(Request $request, $author_id)
    {
        // Könyv létrehozása az adott szerzőhöz
        $this->api->post("/writers/{$author_id}/books", $request->all());
        
        return back();
    }
}