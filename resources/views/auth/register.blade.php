@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Create Account</h4>
            </div>
            <div class="card-body p-4">
                {{-- SECURITY (Module 3): @csrf prevents Cross-Site Request Forgery --}}
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            {{-- SECURITY (Module 1): {{ }} escapes XSS in error messages --}}
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Register As</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role...</option>
                            <option value="donor" {{ old('role') === 'donor' ? 'selected' : '' }}>🏪 Food Donor</option>
                            <option value="ngo" {{ old('role') === 'ngo' ? 'selected' : '' }}>🤝 NGO / Charity</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="orgNameField" style="display:none;">
                        <label for="organization_name" class="form-label">Organization Name</label>
                        <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                               id="organization_name" name="organization_name" value="{{ old('organization_name') }}">
                        @error('organization_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Min 8 chars. Must include uppercase, lowercase, number, and special character.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100">
                        <i class="bi bi-person-plus"></i> Register
                    </button>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted">Already have an account?</span>
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('role').addEventListener('change', function() {
        document.getElementById('orgNameField').style.display = this.value === 'ngo' ? 'block' : 'none';
    });
    // Trigger on page load for old values
    if (document.getElementById('role').value === 'ngo') {
        document.getElementById('orgNameField').style.display = 'block';
    }
</script>
@endpush
