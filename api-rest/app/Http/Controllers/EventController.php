<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Asegúrate de importar Carbon

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date', 'desc')->get(); // Ordenar por fecha de menos a más antigua

        $events->map(function ($event) {
            if ($event->ver_img_path) {
                $event->ver_img_path = Storage::url($event->ver_img_path);
            }
            if ($event->hor_img_path) {
                $event->hor_img_path = Storage::url($event->hor_img_path);
            }
            return $event;
        });

        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $events,
        ], 200);
    }

    public function upcoming()
    {
        $now = Carbon::now()->toDateString(); // Obtener la fecha actual en formato YYYY-MM-DD

        $events = Event::where('date', '>=', $now)
            ->orderBy('date', 'asc') // Ordenar por fecha de más antigua a más reciente
            ->get();

        $events->map(function ($event) {
            if ($event->ver_img_path) {
                $event->ver_img_path = Storage::url($event->ver_img_path);
            }
            if ($event->hor_img_path) {
                $event->hor_img_path = Storage::url($event->hor_img_path);
            }
            return $event;
        });

        return response()->json([
            'status' => true,
            'message' => 'Upcoming events retrieved successfully',
            'data' => $events,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:255',
            'ver_img' => 'nullable|image|max:2048',
            'hor_img' => 'nullable|image|max:2048',
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

        $data = $request->only(['title', 'description', 'price', 'date', 'time', 'address', 'lat', 'long']);

        if ($request->hasFile('ver_img')) {
            $image = $request->file('ver_img');
            $path = $image->store('events', 'public');
            $data['ver_img_path'] = $path;
        }

        if ($request->hasFile('hor_img')) {
            $image = $request->file('hor_img');
            $path = $image->store('events', 'public');
            $data['hor_img_path'] = $path;
        }

        $event = new Event($data);
        $event->save();

        if ($event->ver_img_path) {
            $event->ver_img_path = Storage::url($event->ver_img_path);
        }
        if ($event->hor_img_path) {
            $event->hor_img_path = Storage::url($event->hor_img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Event created successfully',
            'data' => $event,
        ], 200);
    }

    public function show(Event $event)
    {
        if ($event->ver_img_path) {
            $event->ver_img_path = Storage::url($event->ver_img_path);
        }
        if ($event->hor_img_path) {
            $event->hor_img_path = Storage::url($event->hor_img_path);
        }

        return response()->json([
            'status' => true,
            'data' => $event
        ], 200);
    }

    public function update(Request $request, Event $event)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:255',
            'ver_img' => 'nullable|image|max:2048',
            'hor_img' => 'nullable|image|max:2048',
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

        $data = $request->only(['title', 'description', 'price', 'date', 'time', 'address', 'lat', 'long']);

        if ($request->hasFile('ver_img')) {
            if ($event->ver_img_path) {
                Storage::disk('public')->delete($event->ver_img_path);
            }
            $image = $request->file('ver_img');
            $path = $image->store('events', 'public');
            $data['ver_img_path'] = $path;
        }

        if ($request->hasFile('hor_img')) {
            if ($event->hor_img_path) {
                Storage::disk('public')->delete($event->hor_img_path);
            }
            $image = $request->file('hor_img');
            $path = $image->store('events', 'public');
            $data['hor_img_path'] = $path;
        }

        $event->update($data);

        if ($event->ver_img_path) {
            $event->ver_img_path = Storage::url($event->ver_img_path);
        }
        if ($event->hor_img_path) {
            $event->hor_img_path = Storage::url($event->hor_img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully',
            'data' => $event,
        ], 200);
    }

    public function destroy(Event $event)
    {
        if ($event->ver_img_path) {
            Storage::disk('public')->delete($event->ver_img_path);
        }
        if ($event->hor_img_path) {
            Storage::disk('public')->delete($event->hor_img_path);
        }

        $event->delete();
        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully'
        ], 200);
    }
}
