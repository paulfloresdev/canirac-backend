<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');

        if ($filter == 1) {
            $services = Service::where('date', '<', Carbon::now()->toDateString())
                ->orderBy('date', 'asc')
                ->get();
        } elseif ($filter == 2) {
            $services = Service::where('date', '>', Carbon::yesterday()->toDateString())
                ->orderBy('date', 'asc')
                ->get();
        } else {
            $services = Service::orderBy('date', 'asc')->get();
        }

        $services->map(function ($service) {
            if ($service->img_path) {
                $service->img_path = Storage::url($service->img_path);
            }
            return $service;
        });

        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $services,
        ], 200);
    }

    public function upcoming()
    {
        $now = Carbon::now()->toDateString();

        $services = Service::where('date', '>=', $now)
            ->orderBy('date', 'asc')
            ->get();

        $services->map(function ($service) {
            if ($service->img_path) {
                $service->img_path = Storage::url($service->img_path);
            }
            return $service;
        });

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
            'partner_price' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:256',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'img' => 'image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['title', 'description', 'partner_price', 'price', 'date', 'time', 'address', 'lat', 'long']);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $path = $image->store('services', 'public');
            $data['img_path'] = $path;
        }

        $service = new Service($data);
        $service->save();

        if ($service->img_path) {
            $service->img_path = Storage::url($service->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Service created successfully',
            'data' => $service,
        ], 200);
    }

    public function show(Service $service)
    {
        if ($service->img_path) {
            $service->img_path = Storage::url($service->img_path);
        }

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
            'partner_price' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:256',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'img' => 'image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['title', 'description', 'partner_price', 'price', 'date', 'time', 'address', 'lat', 'long']);

        if ($request->hasFile('img')) {
            if ($service->img_path) {
                Storage::disk('public')->delete($service->img_path);
            }
            $image = $request->file('img');
            $path = $image->store('services', 'public');
            $data['img_path'] = $path;
        }

        $service->update($data);

        if ($service->img_path) {
            $service->img_path = Storage::url($service->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Service updated successfully',
            'data' => $service,
        ], 200);
    }

    public function updateDetails(Request $request, Service $service)
    {
        $rules = [
            'title' => 'required|string|max:256',
            'description' => 'required|string|max:2048',
            'partner_price' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:256',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['title', 'description', 'partner_price', 'price', 'date', 'time', 'address', 'lat', 'long']);

        $service->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Service details updated successfully',
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

            $service->img_path = Storage::url($service->img_path);
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
