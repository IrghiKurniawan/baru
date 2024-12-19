<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use App\Models\Report;

class ResponseController extends Controller
{
    public function index()
    {
        // Ambil input provinsi dari query string (jika ada)
        $selectedProvince = request()->input('province');
    
        // Filter laporan berdasarkan provinsi yang dipilih
        $responses = Report::when($selectedProvince, function ($query, $selectedProvince) {
            return $query->where('province', $selectedProvince);
        })->get();
    
        // Ambil daftar provinsi unik untuk dropdown
        $provinces = Report::select('province')->distinct()->get();
    
        return view('response.index', compact('responses', 'provinces', 'selectedProvince'));
    }

    public function create()
    {
        //
    }


    public function store($id, Request $request)
    {
        // Cari report berdasarkan ID
       
        $report = Report::find($id);

        response::create([
            'report_id' => $report->id,
            'response_status' => $request->input('response_status'),
        ]);

        // Redirect ke halaman detail laporan
        return redirect()->back();
    }



    public function show($id)
    {
        // Cari report berdasarkan ID
        $report = Report::find($id);

        // Pastikan bahwa report_id ada untuk menemukan Response
        // Cari Response berdasarkan report_id
        $response = $report;

        return view('response.detail-response', compact('response', 'report'));
    }



    public function edit(Response $response)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // Find the response associated with the report ID
        $response = Response::where('report_id', $id)->first();
    
        // If no response found for this report
        if (!$response) {
            return redirect()->back()->with('error', 'Tidak ada respons yang ditemukan untuk laporan ini');
        }
    
        // Update response_status
        $response->update([
            'response_status' => $request->input('response_status'),
        ]);
    
        // Redirect with success message
        return redirect()->route('response.show', $response->report_id)->with('success', 'Status berhasil diperbarui');
    }
    

    public function destroy(Response $response)
    {
        //
    }
}
