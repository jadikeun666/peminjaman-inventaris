@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div class="row justify-content-center">
<div class="col-md-7">

    <h4 class="fw-semibold mb-1">Profil Saya</h4>
    <p class="text-muted mb-4">Kelola informasi akun kamu</p>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    {{-- Info Profil --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:60px; height:60px; font-size:1.5rem; flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold fs-5">{{ Auth::user()->name }}</div>
                    <div class="text-muted small">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-6">
                    <div class="bg-light rounded p-3">
                        <div class="text-muted small mb-1">NPM</div>
                        <div class="fw-semibold">{{ Auth::user()->npm ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded p-3">
                        <div class="text-muted small mb-1">No. HP</div>
                        <div class="fw-semibold">{{ Auth::user()->no_hp ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-light rounded p-3">
                        <div class="text-muted small mb-1">Bergabung sejak</div>
                        <div class="fw-semibold">
                            {{ Auth::user()->created_at->format('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Edit Profil --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Edit Informasi</h6>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', Auth::user()->name) }}"
                           maxlength="30" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', Auth::user()->email) }}"
                           required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">NPM</label>
                        <input type="number" name="npm" class="form-control"
                               value="{{ old('npm', Auth::user()->npm) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">No. HP</label>
                        <input type="number" name="no_hp" class="form-control"
                               value="{{ old('no_hp', Auth::user()->no_hp) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Form Ganti Password --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Ganti Password</h6>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="password_lama"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password"
                           class="form-control" minlength="6" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-outline-danger w-100">
                    Ganti Password
                </button>
            </form>
        </div>
    </div>

</div>
</div>
@endsection