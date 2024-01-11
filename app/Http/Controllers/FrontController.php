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
        'status' => 'required|string|max:255',
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
    'status' => $request->input('status'),
    ]);

    $newApplication = Applications::all();
    return response()->json(['success' => true, 'applications' => $newApplication], 200);
    }

    // Get applications
    public function getAllApps()
    {
    $applications = Applications::all();
    return response()->json(['applications' => $applications], 200);
    }

    // Search application
    public function searchApps(Request $request)
    {
    $searchTerm = $request->input('search_term');
    
    $applications = Applications::where('account_number', $searchTerm)
        ->orWhere('specialist_name', 'like', "%$searchTerm%")
        ->orWhere('phonenum', $searchTerm)
        ->get();

    if ($applications->isEmpty()) {
        return response()->json(['message' => 'No applications found for the given search term'], 404);
    }

    return response()->json(['applications' => $applications], 200);
    }

    // Update application
    public function updateApp(Request $request, $id)
    {
        $application = Applications::find($id);

        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'specialist_name' => 'required|string|max:255',
            'phonenum' => 'required|string|max:15',
            'topic' => 'required|string|max:255',
            'account_number' => 'required|digits:5',
            'createddate' => 'required|string|max:10',
            'comment' => 'nullable|string',
            'job_title' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($application->status == 'Завершен') {
            return response()->json(['status'  => 'Нельзя изменить статус так как заявка завершена'], 400);
        }

        $application->update([
            'specialist_name' => $request->input('specialist_name'),
            'phonenum' => $request->input('phonenum'),
            'topic' => $request->input('topic'),
            'account_number' => $request->input('account_number'),
            'createddate' => $request->input('createddate'),
            'comment' => $request->input('comment'),
            'job_title' => $request->input('job_title'),
            'status' => $request->input('status'),
        ]);

         $applications = Applications::all();

        return response()->json(['success' => true, 'applications' => $applications], 200);
    }

    // Delete application
    public function deleteApp($id) {
    $newApplication = Applications::find($id);

    if (!$newApplication) {
        return response()->json(['error' => 'Application not found'], 404);
    }

    $newApplication->delete();

    $newApplication = Applications::all();

    return response()->json(['success' => true, 'applications' => $newApplication], 200);
    }
}

