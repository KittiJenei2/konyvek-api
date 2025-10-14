<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;
use App\Models\WriterModel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = BookModel::with('writer');

    if ($request->filled('author_id')) {
        $query->where('author_id', $request->author_id);
    }

    if ($request->filled('genre')) {
        $query->where('genre', $request->genre);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('genre', 'like', "%{$search}%");
        });
    }

    $books = $query->get();
    $writers = WriterModel::all();

    $genres = BookModel::select('genre')->distinct()->get()->pluck('genre');
    return view('books.index', compact('books', 'writers', 'genres'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $writers = WriterModel::all();
        return view('books.create', compact('writers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=> 'required|string|max:255',
            'author_id'=>'required|exists:writers,id',
            'price'=> 'required|numeric',
            'iban'=> 'nullable|string',
            'description'=> 'nullable|string',
            'genre'=> 'nullable|string',
            'image'=> 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
        }

        BookModel::create([
        'title' => $request->title,
        'author_id' => $request->author_id,
        'price' => $request->price,
        'iban' => $request->iban,
        'description' => $request->description,
        'genre' => $request->genre,
        'image_path' => $imagePath,
        ]);

        return redirect()->route('books.index')->with('success','Book successfully added to our library.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $books = BookModel::find($id);
        return view('books.show', compact('books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $books = BookModel::find($id);
        $writers = WriterModel::all();
        return view('books.edit', compact('books', 'writers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
    'title'=> 'required|string|max:255',
    'author_id'=>'required|exists:writers,id',
    'price'=> 'required|numeric',
    'iban'=> 'nullable|string',
    'description'=> 'nullable|string',
    'genre'=> 'nullable|string',
    'image'=> 'nullable|image',
]);

        $books = BookModel::find($id);
        $books->title = $request->title;
        $books->author_id = $request->author_id;
        $books->price = $request->price;
        $books->iban = $request->iban;
        $books->description = $request->description;
        $books->genre = $request->genre;
        if ($request->hasFile('image')) {
    $books->image_path = $request->file('image')->store('books', 'public');
}
        $books->save();

        return redirect()->route('books.index')->with('success','Edited.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $books = BookModel::find($id);
        $books->delete();

        return redirect()->route('books.index')->with('success', 'Book successfully deleted. ');
    }
}
