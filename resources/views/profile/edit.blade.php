@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
            <h2 class="mb-0">Profile Settings</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success animate-slide-up">{{ session('success') }}</div>
        @endif

        {{-- Profile Information --}}
        <div class="card shadow-sm mb-4 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="card-header border-bottom border-dark py-3">
                <h5 class="mb-0"><i class="bi bi-person-circle text-apple-accent"></i> Profile Information</h5>
                <small class="text-muted">Update your account's profile information and contact details.</small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="d-flex align-items-center mb-4">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#profilePhotoModal">
                            <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="rounded-circle me-3 border border-dark shadow-sm" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                        </a>
                        <div>
                            <label for="photo" class="form-label mb-1">Profile Photo</label>
                            <input type="file" id="photo" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/gif">
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if($user->isNgo())
                    <div class="mb-3">
                        <label for="organization_name" class="form-label">Organization Name</label>
                        <input id="organization_name" name="organization_name" type="text" class="form-control @error('organization_name') is-invalid @enderror" value="{{ old('organization_name', $user->organization_name) }}" required autocomplete="organization">
                        @error('organization_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required autocomplete="tel">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="notification_preference" class="form-label">Notification Preference</label>
                            <select id="notification_preference" name="notification_preference" class="form-select @error('notification_preference') is-invalid @enderror" required>
                                <option value="email" {{ old('notification_preference', $user->notification_preference) === 'email' ? 'selected' : '' }}>Email Only</option>
                                <option value="sms" {{ old('notification_preference', $user->notification_preference) === 'sms' ? 'selected' : '' }}>SMS Only</option>
                                <option value="both" {{ old('notification_preference', $user->notification_preference) === 'both' ? 'selected' : '' }}>Email & SMS</option>
                            </select>
                            @error('notification_preference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-ns-primary">Save Profile</button>
                        @if($user->profile_photo_path)
                            <button type="submit" form="removePhotoForm" class="btn btn-outline-danger">Remove Photo</button>
                        @endif
                    </div>
                </form>
                
                @if($user->profile_photo_path)
                <form id="removePhotoForm" method="POST" action="{{ route('profile.photo.destroy') }}" class="d-none">
                    @csrf
                    @method('delete')
                </form>
                @endif
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card shadow-sm animate-slide-up" style="animation-delay: 0.2s;">
            <div class="card-header border-bottom border-dark py-3">
                <h5 class="mb-0"><i class="bi bi-shield-lock text-apple-danger"></i> Update Password</h5>
                <small class="text-muted">Ensure your account is using a long, random password to stay secure.</small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required autocomplete="current-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input id="password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-outline-light text-apple-danger border-apple-danger" style="background-color: rgba(255,59,48,0.1);">Change Password</button>

                        @if (session('status') === 'password-updated')
                            <p class="text-apple-success mb-0 small">Saved.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Photo Modal -->
<div class="modal fade" id="profilePhotoModal" tabindex="-1" aria-labelledby="profilePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--apple-surface); border: 1px solid var(--apple-border);">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-4">
                <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="img-fluid rounded shadow" style="max-height: 400px; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

@endsection
