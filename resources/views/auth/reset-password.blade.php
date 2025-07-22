<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.user.head')
    <title>Reset Password</title>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4 fw-bold text-primary">Reset Password</h4>

                    @if (session('status'))
                        <div class="alert alert-success rounded-3">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="formReset" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control form-control-lg bg-opacity-25 rounded-3"
                                   name="password" id="password" required placeholder="Minimal 8 karakter" minlength="8">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control form-control-lg bg-opacity-25 rounded-3"
                                   name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password" minlength="8">
                        </div>

                        <div class="d-grid">
                            <button type="button" id="submit_admin" onclick="submit_form(this,'#formReset')" class="btn btn-primary">
                            <span class="indicator-label">Reset Password</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.user.script')

</body>
</html>
