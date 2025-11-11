<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;
use App\Models\WriterModel;

class BookController extends Controller
{
    public function index()
    {
        $books = BookModel::all();
        return response()->json(['books' => $books]);
    }

    public function store(Request $request)
    {
        $books = BookModel::create($request->all());
        return response()->json(['books' => $books]);
    }

    public function update(Request $request, $id)
    {
        $books = BookModel::findOrFail($id);
        $books->update($request->all());

        return response()->json(['books' => $books]);
    }

    public function destroy($id){
        $books = BookModel::findOrFail($id);
        $books->delete();

        return response()->json(['id' => $id]);
    }
}
