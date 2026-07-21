@extends('adminlte::page')

@section('title', 'Profil Pengguna - SPPG')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-user-cog mr-2"></i>Pengaturan Profil</h1>
            <p class="text-muted mb-0">Kelola informasi data diri dan kata sandi akun Anda</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
            </a>
        </div>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Gagal menyimpan perubahan:</strong>
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

    <div class="row">
        <!-- Kolom Kiri: Kartu Identitas Profil -->
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm text-center">
                <div class="card-body box-profile py-4">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex justify-content-center align-items-center bg-light rounded-circle shadow-sm" style="width: 100px; height: 100px;">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                    </div>

                    <h3 class="profile-username text-center font-weight-bold mb-1">
                        {{ $user->nama ?? $user->name }}
                    </h3>

                    <p class="text-muted text-center mb-2">
                        {{ $user->username ?? $user->email }}
                    </p>

                    <div class="mb-3">
                        <span class="badge badge-primary text-uppercase px-3 py-2 shadow-xs">
                            <i class="fas fa-shield-alt mr-1"></i> {{ $user->role ?? 'User' }}
                        </span>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3 text-left">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-id-badge mr-2 text-info"></i>ID Pengguna</span>
                            <span class="font-weight-bold">#{{ $user->id_user ?? $user->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-shield mr-2 text-warning"></i>Hak Akses</span>
                            <span class="font-weight-bold text-capitalize">{{ $user->role ?? 'User' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            <span><i class="fas fa-calendar-alt mr-2 text-success"></i>Terdaftar Sejak</span>
                            <span class="font-weight-bold">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                        </li>
                    </ul>

                    <button class="btn btn-danger btn-block font-weight-bold mt-3" 
                            onclick="event.preventDefault(); document.getElementById('logout-form-profile').submit();">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log Out / Keluar
                    </button>
                    <form id="logout-form-profile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit Profil & Password -->
        <div class="col-md-8">
            <!-- Form Informasi Diri -->
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit mr-2 text-primary"></i>Informasi Akun
                    </h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" 
                                       value="{{ old('nama', $user->nama ?? $user->name) }}" required>
                            </div>
                            @error('nama')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username / Email Login <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" 
                                       value="{{ old('username', $user->username ?? $user->email) }}" required>
                            </div>
                            @error('username')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label>Peran / Role Sistem</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                </div>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $user->role ?? 'User' }}" readonly>
                            </div>
                            <small class="form-text text-muted">Peran pengguna dikelola oleh Administrator sistem.</small>
                        </div>
                    </div>

                    <!-- Section Ganti Kata Sandi -->
                    <div class="card-header border-top border-bottom-0 pt-3">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-key mr-2 text-warning"></i>Ubah Kata Sandi (Opsional)
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <p class="text-muted small mb-3">Kosongkan kolom berikut jika Anda tidak ingin mengubah kata sandi akun Anda.</p>

                        <div class="form-group">
                            <label for="current_password">Kata Sandi Saat Ini</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" placeholder="Masukkan kata sandi lama">
                            </div>
                            @error('current_password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Minimal 6 karakter">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                        </div>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right bg-white border-top">
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
