<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::all();
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $labels,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:512',
        ]);

        $label = Label::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Label created successfully',
            'data' => $label,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        return response()->json([
            'status' => true,
            'data' => $label
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $request->validate([
            'text' => 'required|string|max:512',
        ]);

        $label->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Label updated successfully',
            'data' => $label,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $label->delete();
        return response()->json([
            'status' => true,
            'message' => 'Label deleted successfully',
        ], 200);
    }
}
