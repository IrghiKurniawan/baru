@extends('templates.app')

@section('content')
    <h1>Monitoring</h1>

    <!-- Cek apakah pengguna memiliki laporan -->
    @if ($reports->isEmpty())
        <p>Belum pernah melaporkan apapun.</p>
    @else
        @foreach ($reports as $key => $report)
        <div class="container py-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Report Details</h4>
                </div>
                <div class="card-body">
                    <div class="header">
                        <h5 class="card-title">Pengaduan : {{ $report->created_at }}</h5>
                    </div>
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" onclick="toggleView('data', {{ $key }})">Data</th>
                                <th scope="col" onclick="toggleView('image', {{ $key }})">Image</th>
                                <th scope="col" onclick="toggleView('status', {{ $key }})">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="content-row" id="data-row-{{ $key }}">
                                <td colspan="3">
                                    <div class="data-content">
                                        <strong>Deskripsi:</strong> {{ $report->description }} <br>
                                        <strong>Email:</strong> {{ $report->user->email }} <br>
                                        <strong>Alamat:</strong> {{ $report->province }} <br>
                                        <strong>Type:</strong> {{ $report->type }}
                                    </div>
                                </td>
                            </tr>
                            <tr class="content-row d-none" id="image-row-{{ $key }}">
                                <td colspan="3">
                                    <div class="image-content">
                                        <strong>Image:</strong><br>
                                        <img src="{{ asset('assets/images/' . ($report->image ?? 'default.jpg')) }}" 
                                        class="card-img-top" 
                                        alt="Image of {{ $report->name ?? 'No Name' }}" 
                                        style="height:auto;">
                                    </div>
                                </td>
                            </tr>
                            <tr class="content-row d-none" id="status-row-{{ $key }}">
                                <td colspan="3">
                                    @if ($report->response && $report->response->response_status)
                                        <div class="status-content">
                                            <strong>Status:</strong>
                                            {{ $report->response->response_status }}
                                        </div>
                                      
                                        @if ($progresses->isEmpty())
                                            <p>Belum ada progress yang tercatat.</p>
                                        @else
                                            @foreach ($progresses as $progress)
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <h6 class="card-subtitle mb-2 text-muted">History Progress:</h6>

                                                        @if (is_array($progress->histories) && count($progress->histories) > 0)
                                                            <ul class="list-group">
                                                                @foreach ($progress->histories as $history)
                                                                    <li class="list-group-item">
                                                                        <strong>{{ \Carbon\Carbon::parse($history['created_at'])->format('d M Y H:i') }}:</strong>
                                                                        {{ $history['response_progress'] }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="text-muted">Tidak ada progress untuk response ini.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @else
                                        <div class="status-content">
                                            <strong>Status:</strong>
                                            Belum ada respon
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setReportId({{ $report->id }})">Hapus</button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('report.destroy', $report->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Pengaduan</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus pengaduan "{{ $report->description }}"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
    @endif

    <!-- Modal Hapus Pengaduan -->
  
    <!-- JavaScript Toggle View -->
    <script>
        function toggleView(view, reportIndex) {
            // Find the closest card to the clicked element
            const card = event.target.closest('.card');
            
            // Within this card, hide all content rows
            card.querySelectorAll('.content-row').forEach(row => row.classList.add('d-none'));

            // Show the specific row based on the view and report index
            const targetRow = card.querySelector(`#${view}-row-${reportIndex}`);
            if (targetRow) {
                targetRow.classList.remove('d-none');
            }
        }
    </script>

    <style>
        .data-content, .image-content, .status-content {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Optional, adds space between items */
        }
    </style>
@endsection
