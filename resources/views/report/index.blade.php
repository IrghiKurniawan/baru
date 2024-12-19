@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('report.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambahkan Laporan
                </a>
            </div>

            <div class="col-12 mb-4">
                <form method="GET" action="{{ route('report.index') }}" class="row g-3">
                    <div class="col-md-10">
                        <label for="province" class="form-label">Pilih Provinsi:</label>
                        <select name="province" id="province" class="form-select">
                            <option value="">Semua Provinsi</option>
                        </select>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>

            @forelse($reports as $report)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('assets/images/' . ($report->image ?? 'default.jpg')) }}" class="card-img-top"
                            alt="Image of {{ $report->name ?? 'No Name' }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-text">{{ $report->description ?? 'Deskripsi tidak tersedia' }}</h5>
                            <p class="card-text text-muted">{{ $report['user']['email'] ?? 'Nama tidak tersedia' }}</p>

                            <p id="province-{{ $report->id }}" class="card-text">Lokasi: Memuat...</p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="me-3"><i class="bi bi-eye me-1"></i>{{ $report->viewers ?? '0' }}</span>
                                    <span>
                                        <i class="bi bi-heart me-1" id="love-{{ $report->id }}"
                                            style="{{ $report->voting > 0 ? 'color: red;' : '' }}"
                                            data-report-id="{{ $report->id }}"
                                            onclick="toggleLove({{ $report->id }})"></i>
                                        <span id="voting-count-{{ $report->id }}">{{ $report->voting ?? '0' }}</span>
                                    </span>
                                </div>
                                <span class="badge bg-primary">{{ $report->type ?? 'Tidak diketahui' }}</span>
                                <span class="badge bg-primary">{{ $report->created_at ?? 'Tidak diketahui' }}</span>
                            </div>

                            <a href="{{ route('report.show', ['id' => $report->id]) }}"
                                class="btn btn-primary">Detail</a>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada laporan yang tersedia.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('style')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .card {
                transition: transform 0.3s ease-in-out;
            }

            .card:hover {
                transform: translateY(-10px);
            }
        </style>
    @endpush

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reports = @json($reports);

                reports.forEach(report => {
                    const provinceId = report.province;
                    const provinceElement = document.getElementById(`province-${report.id}`);

                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
                        .then(response => response.json())
                        .then(provinces => {
                            const province = provinces.find(province => province.id == provinceId);
                            const provinceName = province ? province.name : 'Lokasi tidak tersedia';
                            provinceElement.innerText = `Lokasi: ${provinceName}`;
                        })
                        .catch(error => {
                            provinceElement.innerText = 'Lokasi tidak tersedia';
                            console.error('Error fetching province data:', error);
                        });
                });

                const provinceSelect = document.getElementById('province');

                fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.id;
                            option.textContent = province.name;
                            provinceSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            });

            function toggleLove(reportId) {
                const heartIcon = document.getElementById('love-' + reportId);
                const votingCountElement = document.getElementById('voting-count-' + reportId);

                // Periksa apakah ikon sudah berwarna merah (sudah memberi vote)
                let isLoved = heartIcon.style.color === 'red';

                // Jika belum loved, beri warna merah dan kirim request untuk menambah voting
                if (!isLoved) {
                    heartIcon.style.color = 'red';
                    votingCountElement.innerText = parseInt(votingCountElement.innerText) + 1;
                } else {
                    // Jika sudah loved, kembalikan ke warna default dan kirim request untuk mengurangi voting
                    heartIcon.style.color = '';
                    votingCountElement.innerText = parseInt(votingCountElement.innerText) - 1;
                }

                // Kirim permintaan AJAX ke server untuk update voting
                fetch(`/report/${reportId}/vote`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            vote: !isLoved
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Jika ada error, kembalikan state awal
                            heartIcon.style.color = '';
                            votingCountElement.innerText = parseInt(votingCountElement.innerText) - 1;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
@endsection
