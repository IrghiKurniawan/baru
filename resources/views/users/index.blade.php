@extends('templates.app')

@section('content')

@if(Session::get('success'))
<div class="alert alert-success">{{ Session::get('success') }}</div>
@endif 
@if(Session::get('deleted'))
<div class="alert alert-success">{{ Session::get('deleted') }}</div>
@endif 
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Daftar Akun</h3>
            </div>
            <div class="card-header bg-primary">
                <form class="d-flex" action="{{ route('home.akun') }}" role="search" method="GET">
                    <input type="search" name="search" aria-label="Search" class="form-control me-2" placeholder="Cari Users">
                    <button class="btn btn-outline-success" type="submit" style="background-color: white; color: black; border-color: white;">
                        Search
                    </button>
                </form>
            </div>
            <div class="card-body">
                <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah Akun</a>
                <table class="table table-responsive-md table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['password'] }}</td>
                            <td>{{ $user['role'] }}</td>
                            <td class="text-center">
                                <form action="{{route('user.reset' , $user['id'])}}" method="POST">
                                    @csrf
                                    @method('PATCH') 
                                    <button type="submit" class="btn btn-sm btn-outline-info me-2"><i class="fas fa-edit"></i> Reset</button>
                                </form>
                               
                                <button class="btn btn-sm btn-outline-danger" onclick="showModalDelete({{ $user['id'] }}, '{{ $user['name'] }}')"><i class="fas fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="ModalDeleteUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-delete-user" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Data User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data User <span id="nama-user" class="fw-bold"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script>
        function showModalDelete(id,name) {
            let action = '{{route('user.delete', ':id')}}';
            action = action.replace(':id', id);
            $('#form-delete-user').attr('action', action);
            $('#ModalDeleteUser').modal('show');
            $('#nama-user').text(name);
        }
    </script>
    @endpush
@endsection
