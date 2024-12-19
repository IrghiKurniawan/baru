@extends('templates.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4 fw-bold text-primary">Tambah User Baru</h4>
                    <form action="{{ route('user.store') }}" method="POST">
                        @if(Session::get('success'))
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                         @endif   

                        @if($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li class="ml-2">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        
                        @csrf                
                        <div class="mb-4">
                            <label for="email" class="form-label text-muted fw-medium">email</label>
                            <div class="input-group">
                                <input type="email" class="form-control form-control-lg border-0 bg-light" 
                                       name="email" id="email" placeholder="masukan email" value="{{old('email')}}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-muted fw-medium">password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg border-0 bg-light" 
                                       name="password" id="password" placeholder="masukan password" value="{{old('password')}}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label text-muted fw-medium">Pilih Role</label>
                            <select class="form-select form-select-lg border-0 bg-light" name="role" id="role">
                                <option selected disabled hidden>Role</option>
                                <option value="GUEST" {{old('GUEST')}}>GUEST</option>
                                <option value="STAFF" {{old('STAFF')}}>STAFF</option>
                            <option value="HEAD_STAFF" {{old('HEAD_STAFF')}}>Head Staff</option>
                            </select>
                        </div>
                        
                        
                        
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                               Tambah Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border: 1px solid #0d6efd;
    }
    
    .form-control, .form-select {
        transition: all 0.3s ease;
    }
    
    .form-control:hover, .form-select:hover {
        background-color: #e9ecef !important;
    }
    
    .btn-primary {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
</style>
@endsection