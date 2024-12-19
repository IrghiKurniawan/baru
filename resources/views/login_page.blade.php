@extends('templates.app')

@section('content')
<div class="container min-h-screen d-flex align-items-center justify-content-center py-5">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-5">
                @if (Session::get('failed'))
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{Session('failed')}}
                    </div>
                @endif
                
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle text-primary mb-3" style="font-size: 4rem;"></i>
                    <h2 class="fw-bold">Welcome Back!</h2>
                    <p class="text-muted">Please login to your account</p>
                </div>

                <form action="{{route('loginAuth')}}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" 
                                   class="form-control form-control-lg border-0 bg-light" 
                                   name="email" 
                                   id="email" 
                                   placeholder="Enter your email"
                                   required>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" 
                                   class="form-control form-control-lg border-0 bg-light" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Enter your password"
                                   required>
                            <span class="input-group-text bg-light border-0 toggle-password" style="cursor: pointer;">
                                <i class="fas fa-eye text-primary"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    <a href="{{route('register')}}" class="btn btn-outline-primary btn-lg w-100">Register</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* Import Font Awesome and Animate.css */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

/* Custom Styles */
.card {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.form-control:focus {
    box-shadow: none;
    border-color: #0d6efd;
    background: white !important;
}

.input-group-text {
    transition: all 0.3s ease;
}

.input-group:focus-within .input-group-text {
    background: white !important;
    color: #0d6efd;
}

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #0099ff);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
}

/* Input Animation */
.form-control {
    transition: all 0.3s ease;
}

.form-control:focus {
    transform: translateX(5px);
}

/* Loading Animation for Button */
.btn-primary:active {
    transform: scale(0.95);
}

/* Custom Animation for Card Load */
.card {
    animation: fadeInUp 0.5s ease;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Password Visibility
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle eye icon
        const eyeIcon = this.querySelector('i');
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    // Add loading state to button on form submit
    const form = document.querySelector('.login-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush