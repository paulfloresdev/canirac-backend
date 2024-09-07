<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('title', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $services,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:256',
            'description' => 'required|string|max:2048',
            'contact_name' => 'required|string|max:128',
            'phone' => 'required|string|max:13',
            'img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['title', 'description', 'contact_name', 'phone']);

        // Verificar si se subió una imagen y procesarla
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $path = $image->store('services', 'public'); // Guarda solo el path relativo
            $data['img_path'] = $path;
        }

        $service = new Service($data);
        $service->save();

        return response()->json([
            'status' => true,
            'message' => 'Spervice created successfully',
            'data' => $service,
        ], 200);
    }



    public function show(Service $service)
    {
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $service
        ], 200);
    }

    public function update(Request $request, Service $service)
    {
        $rules = [
            'title' => 'required|string|max:256',
            'description' => 'required|string|max:2048',
            'contact_name' => 'required|string|max:128',
            'phone' => 'required|string|max:13'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['title', 'description', 'contact_name', 'phone']);

        $service->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Service updated successfully',
            'data' => $service,
        ], 200);
    }

    public function updateImage(Request $request, Service $service)
    {
        $rules = [
            'img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if ($request->hasFile('img')) {
            if ($service->img_path) {
                Storage::disk('public')->delete($service->img_path);
            }
            $image = $request->file('img');
            $path = $image->store('services', 'public');
            $service->update(['img_path' => $path]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Image updated successfully',
            'data' => $service,
        ], 200);
    }

    public function destroy(Service $service)
    {
        if ($service->img_path) {
            Storage::disk('public')->delete($service->img_path);
        }

        $service->delete();
        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully'
        ], 200);
    }
}
