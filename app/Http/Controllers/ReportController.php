<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Auth;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Response_progress;
use App\Models\User;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;



class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedProvince = request()->input('province');

    // Filter laporan berdasarkan provinsi yang dipilih
    $reports = Report::when($selectedProvince, function ($query, $selectedProvince) {
        return $query->where('province', $selectedProvince);
    })->get();

    return view('report.index', compact('reports', 'selectedProvince',));
    }


    public function monitor()
    {
        // Ambil semua progres yang ada
        $progresses = Response_progress::all();
        
        // Ambil laporan yang terkait dengan pengguna yang sedang login
        $reports = Auth::user()->reports; 
        
        // Kirim data ke view
        return view('report.monitoring', compact('reports', 'progresses'));
    }
    
    






    public function voting(Request $request, $id) {
        $report = Report::find($id);
        $user = auth()->user();
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found.']);
        }
    
        // Mendapatkan daftar ID pengguna yang sudah memberi vote
        $voters = json_decode($report->voting ?? '[]', true);
    
        // Jika vote = true, menambahkan user ke dalam daftar voting jika belum ada
        if ($request->vote) {
            if (!in_array($user->id, $voters)) {
                $voters[] = $user->id;
                $report->voting = json_encode($voters);  // Update voting dengan ID pengguna
                $report->increment('viewers');  // Menambahkan viewers jika vote berhasil
            }
        } else {
            // Jika vote = false, menghapus user dari daftar voting
            $voters = array_filter($voters, fn($voter) => $voter != $user->id);
            $report->voting = json_encode(array_values($voters));  // Update voting
            $report->decrement('viewers');  // Mengurangi viewers jika vote dibatalkan
        }
    
        // Menyimpan perubahan
        $report->save();
    
        return response()->json(['success' => true, 'voting_count' => count($voters)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('report.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'description' => 'required',
                'type' => 'required',
                'province' => 'required',
                'regency' => 'required',
                'subdistrict' => 'required',
                'village' => 'required',
                'image' => 'required|image',
                'statement' => 'accepted',
            ]);
    
            $imageName = null;
    
            // Cek apakah ada file gambar yang di-upload
            if ($request->hasFile('image')) {
                // Nama gambar dengan timestamp untuk menghindari duplikat
                $imageName = time() . '.' . $request->image->extension();
                // Pindahkan file gambar ke folder public/assets/images
                $request->image->move(public_path('assets/images'), $imageName);
            }
    
            // Simpan data laporan ke database
            Report::create(attributes: [
                'statement' => $request->has('statement'),
                'description' => $request->description,
                'type' => $request->type,
                'province' => $request->province,
                'regency' => $request->regency,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'image' => $imageName,
                'user_id' => auth()->id(), // Mengisi dengan ID pengguna yang sedang login
                'email' => auth()->user()->email, // Menyimpan email pengguna yang sedang login
            ]);
    
            // Redirect setelah berhasil menyimpan
            return redirect()->route('report.index')->with('success', 'Report created successfully.');
        } catch (\Exception $e) {
            // Tangkap dan tampilkan error jika ada
            dd($e->getMessage());
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $progresses = Response_progress::all();
        $report = Report::find($id);
        $user = auth()->id();

        $report->increment('viewers');

        $comments = Comment::where('report_id',$id)->get();
        // $report ->increment('viewers')->default(0);
        return view('report.detail-report',compact('report','comments','progresses'));
    }

    // public function comment(Request $request,$id){
    //     $report = Report::find($id);
        
      
    //     return redirect()->route('report.show', $report->id);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Report::find($id)->delete();

        return redirect()->route('report.index')->with('success', 'Report deleted successfully.');
    }



   // Method untuk melakukan ekspor
public function export(Request $request)
{
  
    $filterData = [];

    // Menambahkan filter berdasarkan provinsi jika ada
    if ($request->has('province') && $request->province != '') {
        $filterData['province'] = $request->province;
    }

    // Menambahkan filter berdasarkan tanggal jika a
    // Ekspor laporan dengan filter yang diterapkan
    return Excel::download(new ReportsExport($filterData), 'laporan.xlsx');
}

}
