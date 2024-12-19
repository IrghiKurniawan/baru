<?php
namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterData;

    public function __construct($filterData)
    {
        $this->filterData = $filterData;
    }

    public function collection()
    {
        // Mengambil data laporan berdasarkan filter
        $query = Report::query();

        // Filter berdasarkan provinsi
        if (!empty($this->filterData['province'])) {
            $query->where('province', $this->filterData['province']);
        }

        // Filter berdasarkan tanggal
        if (!empty($this->filterData['start_date']) && !empty($this->filterData['end_date'])) {
            $query->whereBetween('created_at', [
                $this->filterData['start_date'],
                $this->filterData['end_date']
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Gambar & Pengirim',
            'Provinsi',
            'Deskripsi',
            'Voting',
            'Tanggal Pengaduan',
        ];
    }

    public function map($report): array
    {
        return [
            $report->id,
            $report->user->email ?? 'Tidak ada email',
            $report->province ?? 'Tidak ada provinsi',
            $report->description ?? 'Deskripsi tidak tersedia',
            $report->voting ?? '0',
            $report->created_at->format('d M Y H:i'),
        ];
    }
}
