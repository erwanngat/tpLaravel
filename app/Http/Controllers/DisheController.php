<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisheRequest;
use App\Models\Dishe;
use App\Notifications\DisheCreate;
use Illuminate\Http\Request;

class DisheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dishes = Dishe::all();
        if($dishes->isEmpty()){
            return response()->json(['error' => 'No dishe found'], 404);
        }
        return response()->json($dishes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DisheRequest $request)
    {
        $path = $request->file('image')->store('dishes', 'public');
        $path = asset('storage/' . $path);
        $dishe = Dishe::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
            'user_id' => auth()->id(),
        ]);
        auth()->notify(new DisheCreate());
        return response()->json($dishe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dishe = Dishe::find($id);
        if($dishe->isEmpty()){
            return response()->json(['error' => 'No dishe found'], 404);
        }
        return response()->json($dishe, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DisheRequest $request, string $id)
    {
        $dishe = Dishe::find($id);
        if($dishe->isEmpty()){
            return response()->json(['error' => 'No dishe found'], 404);
        }
        Storage::disk('public')->delete($dishe->image);
        $path = $request->file('image')->store('dishes', 'public');
        $dishe->image = $path;
        $dishe->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
            'user_id' => auth()->id(),
        ]);
        return response()->json($dishe, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dishe = Dishe::find($id);
        if($dishe->isEmpty()){
            return response()->json(['error' => 'No dishe found'], 404);
        }
        return response()->json(null, 200);
    }
}
