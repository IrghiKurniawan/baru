@extends('templates.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 mb-4">
            <!-- Form untuk pencarian -->
            <form method="GET" action="{{ route('response') }}" class="row g-3">
                <div class="col-md-10">
                    <label for="province-search" class="form-label">Pilih Provinsi:</label>
                    <select name="province" id="province-search" class="form-select">
                        <option value="">Semua Provinsi</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </form>

            <!-- Form untuk ekspor -->
            <form method="GET" action="{{ route('report.export') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="province-export" class="form-label">Pilih Provinsi untuk Export:</label>
                    <select name="province" id="province-export" class="form-select">
                        <option value="">Semua Provinsi</option>
                        <!-- Option lainnya akan dimuat di sini -->
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100" id="export-button" disabled>Export</button>
                </div>
            </form>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Gambar & Pengirim</th>
                            <th scope="col">Provinsi</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Voting</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($responses as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/' . ($report->image ?? 'default.jpg')) }}" 
                                         alt="Image of {{ $report->name ?? 'No Name' }}" 
                                         class="img-thumbnail me-3" 
                                         style="width: 70px; height: 70px; object-fit: cover;">
                                    <span>{{ $report['user']['email'] ?? 'Nama tidak tersedia' }}</span>
                                </div>
                            </td>
                            <td id="province-{{ $report->id }}">Memuat...</td>
                            <td>{{ $report->description ?? 'Deskripsi tidak tersedia' }}</td>
                            <td>
                                <span class="me-3"><i class="bi bi-eye me-1"></i>{{ $report->voting ?? '0' }}</span>
                            </td>
                            <td>
                                <!-- Mengubah link ke response.show -->
                                <a href="{{ route('response.show', ['id' => $report->id]) }}" class="btn btn-primary">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada laporan yang tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelectSearch = document.getElementById('province-search');
        const provinceSelectExport = document.getElementById('province-export');
        const exportButton = document.getElementById('export-button');
        const reports = @json($responses);
        let availableProvinces = [];  // Menyimpan provinsi yang ada di dalam tabel

        // Ambil data provinsi yang ada dalam tabel
        reports.forEach(report => {
            const provinceId = report.province;
            if (!availableProvinces.includes(provinceId)) {
                availableProvinces.push(provinceId);
            }
        });

        // Mengambil data provinsi dari API untuk dropdown pencarian
        fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
            .then(response => response.json())
            .then(data => {
                // Untuk pencarian (province-search)
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    provinceSelectSearch.appendChild(option);
                });

                // Untuk ekspor (province-export) hanya provinsi yang ada dalam tabel
                data.forEach(province => {
                    if (availableProvinces.includes(province.id)) {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        provinceSelectExport.appendChild(option);
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching provinces:', error);
            });

        // Menambahkan event listener untuk dropdown ekspor untuk mengaktifkan/menonaktifkan tombol export
        provinceSelectExport.addEventListener('change', function() {
            const selectedProvince = provinceSelectExport.value;

            if (selectedProvince === "" || availableProvinces.includes(selectedProvince)) {
                exportButton.disabled = false;  // Aktifkan tombol Export jika provinsi valid dipilih
            } else {
                exportButton.disabled = true;   // Nonaktifkan tombol Export jika tidak ada provinsi yang valid dipilih
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
