@extends('adminlte::page')

@section('title', 'Kelola User - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-users mr-2"></i>Kelola User</h1>
            <p class="text-muted mb-0">Manajemen akun pengguna dan peran akses sistem</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Pengguna</button>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Gagal memproses data:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Akun Pengguna</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Nama Lengkap</th>
                            <th style="width: 25%">Alamat Email</th>
                            <th style="width: 20%">Peran / Role</th>
                            <th style="width: 15%">Tanggal Dibuat</th>
                            <th style="width: 10%" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">
                                {{ $user->name }}
                                @if(Auth::user()->id_user == $user->id_user)
                                    <span class="badge badge-info ml-1">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php $role = strtolower($user->role ?? '') @endphp
                                @if($role == 'admin')
                                    <span class="badge badge-danger text-uppercase"><i class="fas fa-shield-alt mr-1"></i> Admin</span>
                                @elseif($role == 'ahli gizi')
                                    <span class="badge badge-success text-uppercase"><i class="fas fa-heartbeat mr-1"></i> Ahli Gizi</span>
                                @elseif($role == 'kepala dapur')
                                    <span class="badge badge-primary text-uppercase"><i class="fas fa-utensils mr-1"></i> Kepala Dapur</span>
                                @else
                                    <span class="badge badge-secondary text-uppercase">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-warning btn-sm text-white btn-edit" 
                                            data-id="{{ $user->id_user }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-role="{{ $user->role }}"
                                            data-toggle="modal" 
                                            data-target="#modalEdit"
                                            title="Edit Pengguna">
                                        <i class="fas fa-pencil-alt text-white"></i> Edit
                                    </button>
                                    
                                    @if(Auth::user()->id_user != $user->id_user)
                                        <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini dari sistem?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Pengguna"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    @else
                                        <button class="btn btn-light btn-sm text-muted ml-1" disabled title="Anda tidak dapat menghapus diri sendiri"><i class="fas fa-trash"></i> Hapus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('users.create')
    @include('users.edit')
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditUser');
            const editName = document.getElementById('edit_name');
            const editEmail = document.getElementById('edit_email');
            const editRole = document.getElementById('edit_role');
            const editPassword = document.getElementById('edit_password');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const role = this.getAttribute('data-role');

                    formEdit.setAttribute('action', `/users/${id}`);
                    editName.value = name;
                    editEmail.value = email;
                    editRole.value = role;
                    editPassword.value = ''; // Reset password field
                });
            });
        });
    </script>
@endsection

