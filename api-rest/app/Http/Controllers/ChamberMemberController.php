<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChamberMember;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChamberMemberController extends Controller
{
    public function index()
    {
        $members = ChamberMember::all();

        $members->map(function ($member) {


            if ($member->img_path) {
                if ($member->img_path == 'empty') {
                    $member->img_path = Storage::url($member->img_path);
                } else {
                    $member->img_path = asset('storage/' . $member->img_path);
                }
            }
            return $member;
        });

        return response()->json([
            'status' => true,
            'message' => 'Members retrieved successfully',
            'data' => $members,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:128',
            'role' => 'required|string|max:128',
            'img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['name', 'role']);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $path = $image->store('chamber_members', 'public');
            $data['img_path'] = $path;
        }

        $member = new ChamberMember($data);
        $member->save();

        if ($member->img_path) {
            $member->img_path = Storage::url($member->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Member created successfully',
            'data' => $member,
        ], 200);
    }

    public function show(ChamberMember $chamberMember)
    {
        if ($chamberMember->img_path) {
            $chamberMember->img_path = Storage::url($chamberMember->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Member details retrieved successfully',
            'data' => $chamberMember
        ], 200);
    }

    public function update(Request $request, ChamberMember $chamberMember)
    {
        $rules = [
            'name' => 'required|string|max:128',
            'role' => 'required|string|max:128',
            'img' => 'nullable|image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['name', 'role']);

        if ($request->hasFile('img')) {
            if ($chamberMember->img_path) {
                Storage::disk('public')->delete($chamberMember->img_path);
            }
            $image = $request->file('img');
            $path = $image->store('chamber_members', 'public');
            $data['img_path'] = $path;
        }

        $chamberMember->update($data);

        if ($chamberMember->img_path) {
            $chamberMember->img_path = Storage::url($chamberMember->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Member updated successfully',
            'data' => $chamberMember,
        ], 200);
    }

    public function updateData(Request $request, $id)
    {
        $chamberMember = ChamberMember::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:128',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['name']);
        $chamberMember->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Member data updated successfully',
            'data' => $chamberMember,
        ], 200);
    }

    public function updateImage(Request $request, $id)
    {
        $chamberMember = ChamberMember::findOrFail($id);

        $rules = [
            'img' => 'image|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if ($request->hasFile('img')) {
            if ($chamberMember->img_path) {
                Storage::disk('public')->delete($chamberMember->img_path);
            }

            $image = $request->file('img');
            $path = $image->store('chamber_members', 'public');
            $chamberMember->update(['img_path' => $path]);

            $chamberMember->img_path = Storage::url($chamberMember->img_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'Member image updated successfully',
            'data' => $chamberMember,
        ], 200);
    }


    public function destroy(ChamberMember $chamberMember)
    {
        if ($chamberMember->img_path) {
            Storage::disk('public')->delete($chamberMember->img_path);
        }

        $chamberMember->delete();
        return response()->json([
            'status' => true,
            'message' => 'Member deleted successfully'
        ], 200);
    }

    public function deleteMemberImage($id)
    {
        // Busca al miembro en la base de datos
        $member = ChamberMember::find($id);

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Member not found'
            ], 404);
        }

        // Verifica si tiene una imagen almacenada
        if ($member->img_path && $member->img_path !== 'empty') {
            // Elimina la imagen del almacenamiento
            Storage::disk('public')->delete($member->img_path);

            // Actualiza el campo img_path a "empty"
            $member->img_path = 'empty';
            $member->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Image deleted and img_path set to "empty"',
            'data' => $member
        ], 200);
    }
}
