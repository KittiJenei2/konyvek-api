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
        // 1. Validáció
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'iban' => 'required|string|max:20',
            'genre' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048', // Opcionális borítókép
        ]);

        // 2. Fájl előkészítése
        $files = [];
        if ($request->hasFile('image_path')) {
            $files['image_path'] = $request->file('image_path');
        }

        // 3. API hívás (POST /writers/{id}/books)
        // Fontos: Az except-tel kivesszük a fájlt a sima adatok közül
        $response = $this->api->post("/writers/{$author_id}/books", $request->except('image_path'), $files);

        // 4. Válasz kezelése
        if ($response->successful()) {
            return redirect()->route('books.index', $author_id)
                             ->with('success', 'Könyv sikeresen hozzáadva!');
        }

        return back()
            ->withErrors(['api_error' => 'Hiba történt a könyv mentésekor.'])
            ->withInput();
    }

    public function create($author_id)
    {
        return view('books.create', compact('author_id'));
    }


    public function edit($author_id, $id)
    {
        $response = $this->api->get("/writers/{$author_id}/books/{$id}");

        if ($response->successful()) {
            $book = $response->json();
            return view('books.edit', compact('book', 'author_id'));
        }

        return back()->withErrors(['api_error' => 'A könyv nem található (API hiba).']);
    }

    public function update(Request $request, $author_id, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'iban' => 'required|string|max:20',
            'genre' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048',
        ]);

        $files = [];
        if ($request->hasFile('image_path')) {
            $files['image_path'] = $request->file('image_path');
        }

        // JAVÍTÁS: Itt is {$id} kell!
        $endpoint = "/writers/{$author_id}/books/{$id}";
        
        if (!empty($files)) {
            $data = $request->except('image_path');
            $data['_method'] = 'PATCH'; 
            $response = $this->api->post($endpoint, $data, $files);
        } else {
            $response = $this->api->patch($endpoint, $request->except('image_path'));
        }

        if ($response->successful()) {
            return redirect()->route('books.index', $author_id)
                             ->with('success', 'Könyv sikeresen frissítve!');
        }

        return back()->withErrors(['api_error' => 'Hiba történt a frissítéskor.'])->withInput();
    }

        public function destroy($id)
    {
        $response = $this->api->delete("/writers/{$id}/books");

        if ($response->successful()) {
            return redirect()->route('writers.index')->with('success', 'Szerző törölve.');
        }

        return back()->withErrors(['api_error' => 'Hiba a törlésnél.']);
    }
}