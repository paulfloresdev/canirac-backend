<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $memberships,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'size' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price1' => 'required|numeric',
            'price2' => 'required|numeric',
            'price3' => 'required|numeric',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $membership = new Membership($request->input());
        $membership->save();
        return response()->json([
            'status' => true,
            'message' => 'Membership created successfully'
        ], 200);
    }

    public function show(Membership $membership)
    {
        return response()->json([
            'status' => true,
            'data' => $membership
        ], 200);
    }

    public function update(Request $request, Membership $membership)
    {
        $rules = [
            'size' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price1' => 'required|numeric',
            'price2' => 'required|numeric',
            'price3' => 'required|numeric',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $membership->update($request->input());
        return response()->json([
            'status' => true,
            'message' => 'Membership updated successfully'
        ], 200);
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return response()->json([
            'status' => true,
            'message' => 'Membership deleted successfully'
        ], 200);
    }
}
