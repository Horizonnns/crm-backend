<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Applications;


class FrontController extends Controller
{
    // Create application
    public function createApp(Request $request) 
    {
    // Application validation
    $validator = Validator::make($request->all(), [
        'specialist_name' => 'required|string|max:255',
        'phonenum' => 'required|string|max:15',
        'topic' => 'required|string|max:255',
        'account_number' => 'required|digits:5',
        'createddate' => 'required|string|max:10',
        'comment' => 'nullable|string',
        'job_title' => 'nullable|string|max:255', 
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $newApplication = Applications::create([
    'specialist_name' => $request->input('specialist_name'),
    'phonenum' => $request->input('phonenum'),
    'topic' => $request->input('topic'),
    'account_number' => $request->input('account_number'),
    'createddate' => $request->input('createddate'),
    'comment' => $request->input('comment'),
    'job_title' => $request->input('job_title'),
    ]);

    $newApplication = Applications::all();
    return response()->json(['success' => true, 'applications' => $newApplication], 200);
    }

}
