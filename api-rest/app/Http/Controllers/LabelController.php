<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if ($label->id == 2) {
            $label->text = asset('storage/' . $label->text);
        }

        return response()->json([
            'status' => true,
            'data' => $label
        ], 200);
    }

    public function showVideo()
    {
        $label = Label::find(2);
        if ($label->text) {
            $label->text = asset('storage/' . $label->text);
        }

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

    public function updateVideoLabel(Request $request)
    {
        // Validar la solicitud
        $rules = [
            'video' => 'nullable|mimetypes:video/mp4,video/x-msvideo,video/x-matroska,video/quicktime,video/mpeg,video/3gpp,video/x-ms-wmv|max:819200', // máximo 100 MB
        ];


        $validated = $request->validate($rules);

        // Obtener el registro con id 2 de la entidad Label
        $label = Label::find(2); // Encuentra el registro con id 2

        // Verificar si se subió un video
        if ($request->hasFile('video')) {
            // Si el registro ya tiene un video guardado, eliminar el anterior
            if ($label && $label->text) {
                $previousVideoPath = storage_path('app/public/' . $label->text);

                if (file_exists($previousVideoPath)) {
                    // Eliminar el video anterior
                    unlink($previousVideoPath);
                }
            }

            // Subir y almacenar el nuevo video
            $video = $request->file('video');
            $videoPath = $video->store('videos', 'public'); // Almacenar en la carpeta 'videos' en el disco 'public'

            // Actualizar el registro con la nueva ruta del video
            if ($label) {
                $label->text = $videoPath;
                $label->save(); // Guardar los cambios
            }
        }

        // Redirigir o devolver una respuesta
        return response()->json(['message' => 'Video actualizado y guardado con éxito.']);
    }
}
