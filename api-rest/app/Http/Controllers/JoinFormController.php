<?php

namespace App\Http\Controllers;

use App\Models\JoinForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Asegúrate de importar Carbon

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

    public function indexByStatus($status)
    {
        $joinForms = JoinForm::where('status', '=', $status);
        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'data' => $joinForms,
        ], 200);
    }

    public function getCharts()
    {
        $received = JoinForm::all()->count();
        $unattended = JoinForm::where('status', '=', 1)->count();
        $contacted = JoinForm::where('status', '=', 2)->count();
        $failed = JoinForm::where('status', '=', 3)->count();
        $joined = JoinForm::where('status', '=', 4)->count();


        return response()->json([
            'status' => true,
            'message' => 'Query completed successfully',
            'received' => $received,
            'unattended' => $unattended,
            'contacted' => $contacted,
            'failed' => $failed,
            'joined' => $joined,
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

    public function send(Request $request)
    {
        $rules = [
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
            'sm_other' => 'nullable|string|max:512',
            'sv_have_wifi' => 'required|boolean',
            'sv_have_ac' => 'required|boolean',
            'sv_have_live_music' => 'required|boolean',
            'sv_have_deck' => 'required|boolean',
            'sv_have_lounge' => 'required|boolean',
            'sv_lounge_capacity' => 'required|integer',
            'sv_other' => 'nullable|string|max:512',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only([
            'ins_comercial_name',
            'ins_address',
            'ins_hood',
            'ins_cp',
            'ins_email',
            'com_capacity',
            'com_male',
            'com_female',
            'com_open_date',
            'com_license_status',
            'com_license_type',
            'tax_name',
            'tax_rfc',
            'tax_street',
            'tax_hood',
            'tax_cp',
            'tax_locality',
            'tax_payment',
            'con_name',
            'con_role',
            'con_phone',
            'con_email',
            'com_hours',
            'com_line',
            'com_desc',
            'sm_facebook',
            'sm_instagram',
            'sm_twitter',
            'sm_email',
            'sm_phone',
            'sm_web',
            'sm_other',
            'sv_have_wifi',
            'sv_have_ac',
            'sv_have_live_music',
            'sv_have_deck',
            'sv_have_lounge',
            'sv_lounge_capacity',
            'sv_other',
        ]);
        $data['status'] = 1;

        $joinForm = new JoinForm($data);

        $now = Carbon::now()->toDateString();
        $joinForm->arrived_date = $now;

        $joinForm->save();

        return response()->json([
            'status' => true,
            'message' => 'JoinForm sent successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function contacted($id)
    {
        $joinForm = JoinForm::findOrFail($id);

        if ($joinForm->status != 1) {
            return response()->json([
                'status' => true,
                'message' => 'Invalid change of status',
                'data' => $joinForm,
            ], 200);
        }

        $joinForm->status = 2;

        $now = Carbon::now()->toDateString();
        $joinForm->contacted_date = $now;

        $joinForm->save();

        return response()->json([
            'status' => true,
            'message' => 'JoinForm contacted successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function failed($id)
    {
        $joinForm = JoinForm::findOrFail($id);

        if ($joinForm->status != 2 && $joinForm->status != 4) {
            return response()->json([
                'status' => true,
                'message' => 'Invalid change of status',
                'data' => $joinForm,
            ], 200);
        }

        $joinForm->status = 3;

        $now = Carbon::now()->toDateString();
        $joinForm->finished_date = $now;

        $joinForm->save();

        return response()->json([
            'status' => true,
            'message' => 'JoinForm failed successfully',
            'data' => $joinForm,
        ], 200);
    }

    public function joined($id)
    {
        $joinForm = JoinForm::findOrFail($id);

        if ($joinForm->status != 2 && $joinForm->status != 3) {
            return response()->json([
                'status' => true,
                'message' => 'Invalid change of status',
                'data' => $joinForm,
            ], 200);
        }

        $joinForm->status = 4;

        $now = Carbon::now()->toDateString();
        $joinForm->finished_date = $now;

        $joinForm->save();

        return response()->json([
            'status' => true,
            'message' => 'JoinForm joined successfully',
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
            'status' => 'boolean'
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
