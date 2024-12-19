<?php

namespace App\Http\Controllers;

use App\Models\Response_progress;
use Illuminate\Http\Request;
use App\Models\Response;


class ResponseProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id) {
        // Find the Response based on ID
        // dd($request->all());
        $response = Response::where('report_id', $id)->first();
        
        $validated = $request->validate([
            'response_progress' => 'required|string|max:1000'
        ]);
    
        $responseProgress = Response_progress::firstOrNew([
            'response_id' => $response->id
        ]);
    
        $histories = $responseProgress->histories ?? [];
        $histories[] = [
            'response_progress' => $validated['response_progress'],
            'created_at' => now()->toDateTimeString()
        ];
    
        $responseProgress->histories = $histories;
        $responseProgress->save();
    
        return redirect()->back()->with('success', 'Progress berhasil ditambahkan');
        // Validate data received
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Response_progress $response_progress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Response_progress $response_progress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Response_progress $response_progress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Response_progress $response_progress)
    {
        //
    }
}
