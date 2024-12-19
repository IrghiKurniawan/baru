@extends('templates.app')

@push('styles')
<style>
    .card-img-top {
        transition: transform 0.3s ease-in-out;
    }
    .card-img-top:hover {
        transform: scale(1.05);
    }

    .comment-card {
        opacity: 0;
        animation: fadeInUp 0.6s forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .comment-form {
        transition: all 0.3s ease;
    }
    .comment-form:hover {
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden">
                <img src="{{ asset('assets/images/' . (old('image', $report->image) ?? 'default.jpg')) }}" 
                     class="card-img-top img-fluid" 
                     alt="Image of {{ old('name', $report->name) ?? 'No Name' }}" 
                     style="height: 400px; object-fit: cover;">
                <div class="card-body p-4">
                    <h2 class="card-title mb-3 fw-bold">{{ old('name', $report->description) ?? 'Nama tidak tersedia' }} 🔍</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Penulis:</strong> {{ old('user', $report->user->email) ?? 'Penulis tidak tersedia' }} 👤</p>
                            <p class="mb-2"><strong>Tanggal:</strong> {{ old('date', $report->created_at->format('Y-m-d')) ?? 'Tanggal tidak tersedia' }} 📅</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Type:</strong> {{ old('type', $report->type) ?? 'Tidak diketahui' }} 🏷️</p>
                            <p class="mb-2">
                                <strong>Status:</strong> 
                                <span class="badge {{ $report->response?->response_status == 'DONE' ? 'bg-success' : ($report->response?->response_status == 'REJECTED' ? 'bg-danger' : ($report->response?->response_status == 'ON_PROGRESS' ? 'bg-primary' : 'bg-warning')) }}">
                                    {{ ucfirst($report->response?->response_status) }}
                                </span>
                                
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Komentar 💬</h2>
        
        <div class="row g-4">
            <div class="col-12">
                @forelse ($comments as $index => $comment)
                <div class="card comment-card border-0 shadow-sm mb-3" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="card-title mb-0 me-2">{{ $comment['user']['email'] }}</h5>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="card-text">{{ $comment->comment }}</p>
                    </div>
                </div>
                @empty
                <div class="alert alert-info text-center" role="alert">
                    Belum ada komentar 🤷‍♀️
                </div>
                @endforelse
            </div>
        </div>

        <!-- Form Tambah Komentar -->
        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-lg comment-form border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">Tambah Komentar 💡</h4>
                        <form action="{{ route('report.comment', $report->id) }}" method="post">
                            @csrf
                            <div class="form-floating mb-3">
                                <textarea 
                                    class="form-control @error('comment') is-invalid @enderror" 
                                    id="comment" 
                                    name="comment" 
                                    style="height: 150px;" 
                                    placeholder="Tulis komentar Anda di sini..."
                                    required
                                ></textarea>
                                <label for="comment">Tulis komentar Anda 📝</label>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                Kirim Komentar 🚀
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
            

    // Optional: Add any additional interactivity if needed
    document.addEventListener('DOMContentLoaded', function() {
        const commentCards = document.querySelectorAll('.comment-card');
        commentCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush