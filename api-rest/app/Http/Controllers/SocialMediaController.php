<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{

    public function index()
    {
        $socialMedias = SocialMedia::all();

        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully.',
            'data' => $socialMedias,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:24',
            'label' => 'required|string|max:128',
            'url' => 'required|string|max:512',
        ]);

        $socialMedia = SocialMedia::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia created successfully.',
            'data' => $socialMedia,
        ], 201);
    }


    public function show(SocialMedia $socialMedia)
    {
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully.',
            'data' => $socialMedia,
        ], 200);
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $request->validate([
            'type' => 'required|string|max:24',
            'label' => 'required|string|max:128',
            'url' => 'required|string|max:512',
        ]);

        $socialMedia->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia updated successfully.',
            'data' => $socialMedia,
        ], 200);
    }

    public function destroy(SocialMedia $socialMedia)
    {
        $socialMedia->delete();

        return response()->json([
            'status' => true,
            'message' => 'SocialMedia deleted successfully.',
        ], 200);
    }
}
