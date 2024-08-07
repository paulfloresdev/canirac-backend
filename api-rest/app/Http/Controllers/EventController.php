<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Asegúrate de importar Carbon

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');

        if ($filter == 1) {
            // Obtener eventos con fecha anterior a la fecha actual, ordenados de menos antigua a más antigua
            $events = Event::where('date', '<', Carbon::now()->toDateString())
                ->orderBy('date', 'asc')
                ->get();
        } elseif ($filter == 2) {
            // Obtener eventos con fecha posterior al día de ayer, ordenados de más antigua a menos antigua
            $events = Event::where('date', '>', Carbon::yesterday()->toDateString())
                ->orderBy('date', 'asc')
                ->get();
        } else {
            // Obtener todos los eventos ordenados por fecha de menos a más antigua
            $events = Event::orderBy('date', 'asc')->get();
        }

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
            'message' => 'Query completed successfully',
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
            'message' => 'Query completed successfully',
            'data' => $event
        ], 200);
    }

    // Método existente para actualizar un evento
    public function update(Request $request, Event $event)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'ver_img' => 'nullable|image|max:2048',
            'hor_img' => 'nullable|image|max:2048',
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

    // Nuevo método para actualizar la información del evento excluyendo imágenes
    public function updateDetails(Request $request, Event $event)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'price' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|string|max:32',
            'address' => 'required|string|max:255',
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

        $event->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Event details updated successfully',
            'data' => $event,
        ], 200);
    }

    // Nuevo método para actualizar solo las imágenes del evento
    public function updateVerticalImage(Request $request, Event $event)
    {
        $rules = [
            'ver_img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = [];

        if ($request->hasFile('ver_img')) {
            if ($event->ver_img_path) {
                Storage::disk('public')->delete($event->ver_img_path);
            }
            $image = $request->file('ver_img');
            $path = $image->store('events', 'public');
            $data['ver_img_path'] = $path;
        }

        if (!empty($data)) {
            $event->update($data);
        }

        if ($event->ver_img_path) {
            $event->ver_img_path = Storage::url($event->ver_img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Vertical image updated successfully',
            'data' => $event,
        ], 200);
    }

    public function updateHorizontalImage(Request $request, Event $event)
    {
        $rules = [
            'hor_img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = [];

        if ($request->hasFile('hor_img')) {
            if ($event->hor_img_path) {
                Storage::disk('public')->delete($event->hor_img_path);
            }
            $image = $request->file('hor_img');
            $path = $image->store('events', 'public');
            $data['hor_img_path'] = $path;
        }

        if (!empty($data)) {
            $event->update($data);
        }

        if ($event->hor_img_path) {
            $event->hor_img_path = Storage::url($event->hor_img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Horizontal image updated successfully',
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
