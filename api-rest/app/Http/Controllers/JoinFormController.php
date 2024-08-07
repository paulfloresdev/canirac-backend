<?php

namespace App\Http\Controllers;

use App\Models\JoinForm;
use Illuminate\Http\Request;

class JoinFormController extends Controller
{
    public function index()
    {
        $joinForms = JoinForm::all();
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $joinForms,
        ], 200);
    }

    public function show($id)
    {
        $joinForm = JoinForm::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ins_comercial_name' => 'required|string|max:128',
            'ins_address' => 'required|string|max:256',
            'ins_hood' => 'required|string|max:128',
            'ins_cp' => 'required|string|max:10',
            'ins_email' => 'required|email|max:256',
            'com_capacity' => 'required|integer',
            'com_male' => 'required|integer',
            'com_female' => 'required|integer',
            'com_open_date' => 'required|date',
            'com_license_status' => 'required|string|max:2',
            'com_license_type' => 'required|string|max:2',
            'tax_name' => 'required|string|max:128',
            'tax_rfc' => 'required|string|max:16',
            'tax_street' => 'required|string|max:128',
            'tax_hood' => 'required|string|max:128',
            'tax_cp' => 'required|string|max:10',
            'tax_locality' => 'required|string|max:128',
            'tax_payment' => 'required|string|max:2',
            'con_name' => 'required|string|max:128',
            'con_role' => 'required|string|max:96',
            'con_phone' => 'required|string|max:13',
            'con_email' => 'required|email|max:256',
            'com_hours' => 'required|string|max:128',
            'com_line' => 'required|string|max:1024',
            'com_desc' => 'required|string|max:1024',
            'sm_facebook' => 'required|string|max:512',
            'sm_instagram' => 'required|string|max:512',
            'sm_twitter' => 'required|string|max:512',
            'sm_email' => 'required|email|max:256',
            'sm_phone' => 'required|string|max:13',
            'sm_web' => 'required|string|max:512',
            'sm_other' => 'required|string|max:512',
            'sv_have_wifi' => 'required|boolean',
            'sv_have_ac' => 'required|boolean',
            'sv_have_live_music' => 'required|boolean',
            'sv_have_deck' => 'required|boolean',
            'sv_have_lounge' => 'required|boolean',
            'sv_lounge_capacity' => 'required|integer',
            'sv_other' => 'required|string|max:512',
        ]);

        $joinForm = JoinForm::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'JoinForm created successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $joinForm = JoinForm::findOrFail($id);

        $validated = $request->validate([
            'ins_comercial_name' => 'string|max:128',
            'ins_address' => 'string|max:256',
            'ins_hood' => 'string|max:128',
            'ins_cp' => 'string|max:10',
            'ins_email' => 'email|max:256',
            'com_capacity' => 'integer',
            'com_male' => 'integer',
            'com_female' => 'integer',
            'com_open_date' => 'date',
            'com_license_status' => 'string|max:2',
            'com_license_type' => 'string|max:2',
            'tax_name' => 'string|max:128',
            'tax_rfc' => 'string|max:16',
            'tax_street' => 'string|max:128',
            'tax_hood' => 'string|max:128',
            'tax_cp' => 'string|max:10',
            'tax_locality' => 'string|max:128',
            'tax_payment' => 'string|max:2',
            'con_name' => 'string|max:128',
            'con_role' => 'string|max:96',
            'con_phone' => 'string|max:13',
            'con_email' => 'email|max:256',
            'com_hours' => 'string|max:128',
            'com_line' => 'string|max:1024',
            'com_desc' => 'string|max:1024',
            'sm_facebook' => 'string|max:512',
            'sm_instagram' => 'string|max:512',
            'sm_twitter' => 'string|max:512',
            'sm_email' => 'email|max:256',
            'sm_phone' => 'string|max:13',
            'sm_web' => 'string|max:512',
            'sm_other' => 'string|max:512',
            'sv_have_wifi' => 'boolean',
            'sv_have_ac' => 'boolean',
            'sv_have_live_music' => 'boolean',
            'sv_have_deck' => 'boolean',
            'sv_have_lounge' => 'boolean',
            'sv_lounge_capacity' => 'integer',
            'sv_other' => 'string|max:512',
        ]);

        $joinForm->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'JoinForm updated successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function destroy($id)
    {
        $joinForm = JoinForm::findOrFail($id);
        $joinForm->delete();

        return response()->json([
            'status' => true,
            'message' => 'JoinForm deleted successfully',
        ], 200);
    }
}
