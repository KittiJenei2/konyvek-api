<?php

namespace App\Http\Controllers;

use App\Http\Requests\WriterRequest;
use App\Models\WriterModel;

class WriterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $writers = WriterModel::all();
        return response()->json(['writers' => $writers]);
        //return view('writers.index', compact('writers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('writers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WriterRequest $request)
    {
       
    $portraitPath = null;
    if ($request->hasFile('portrait')) {
        $portraitPath = $request->file('portrait')->store('writers', 'public');
    }

    /*WriterModel::create([
        'name' => $request->name,
        'bio' => $request->bio,
        'portrait_path' => $portraitPath, 
    ]);*/
    $writer = WriterModel::create($request->all());

    return response()->json(['writer' => $writer]);
    //return redirect()->back()->with('success', 'Author created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $writers = WriterModel::find($id);
        return view('writers.show', compact('writers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $writers = WriterModel::find($id);
        return view('writers.edit', compact('writers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WriterRequest $request, string $id)
    {
        $writers = WriterModel::findOrFail($id);
        $writers->update($request->all());
        /*$writers->name = $request->name;
        $writers->bio = $request->bio;
        if ($request->hasFile('portrait')) {
            $writers->portrait_path = $request->file('portrait')->store('writers', 'public');
        }*/

        //return redirect()->route('writers.index')->with('success', 'Author successfully updated.');
        return response()->json(['writer' => $writers]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $writers = WriterModel::findOrFail($id);
        $writers->delete();

        /*return redirect()->route('writers.index')->with('success', 'Author successfully deleted. ');*/
        return response()->json(['message' => 'Deleted successfully.', 'id' => $id]);
    }
}
