@extends('layouts.app')
@section('title', 'Enter OTP Code - Step 2')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-shield-check"></i> Enter Verification Code</h4>
                <div class="text-muted small mt-1">Step 2 of 3: Enter 6-Digit OTP</div>
            </div>
            <div class="card-body p-4">
                <p class="text-center text-muted small mb-3">
                    We sent a 6-digit code to <strong class="text-white">{{ $email }}</strong>. Please enter it below to verify your request.
                </p>




                @if (session('status'))
                    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="background: rgba(52, 199, 89, 0.15); color: #34c759; border: 1px solid rgba(52, 199, 89, 0.3) !important; border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="otp" id="fullOtpInput">

                    <!-- 6-digit OTP Input Group -->
                    <div class="d-flex justify-content-center gap-2 mb-4 mt-2">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" 
                                   class="form-control text-center fs-3 font-monospace otp-box" 
                                   maxlength="1" 
                                   pattern="[0-9]" 
                                   inputmode="numeric"
                                   data-index="{{ $i }}"
                                   style="width: 48px; height: 56px; border-radius: 12px; border: 1px solid var(--apple-border); background: var(--apple-input-bg);"
                                   required 
                                   {{ $i === 0 ? 'autofocus' : '' }}>
                        @endfor
                    </div>

                    @error('otp')
                        <div class="invalid-feedback d-block text-center mb-3 fs-6">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-ns-accent w-100 mb-3 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Verify OTP Code
                    </button>
                </form>

                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2" style="border-color: var(--apple-border) !important;">
                    <a href="{{ route('password.request') }}" class="text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Change Email
                    </a>

                    <form method="POST" action="{{ route('password.otp.resend') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="btn btn-link p-0 text-decoration-none small text-apple-accent" id="resendBtn">
                            Resend OTP Code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.otp-box');
    const fullInput = document.getElementById('fullOtpInput');
    const form = document.getElementById('otpForm');

    function syncOtp() {
        let code = '';
        boxes.forEach(box => {
            code += box.value;
        });
        fullInput.value = code;
    }

    boxes.forEach((box, idx) => {
        box.addEventListener('input', function(e) {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val;
            
            if (val && idx < boxes.length - 1) {
                boxes[idx + 1].focus();
            }
            syncOtp();
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                boxes[idx - 1].focus();
            }
        });

        box.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
            const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
            
            for (let i = 0; i < digits.length; i++) {
                if (boxes[i]) {
                    boxes[i].value = digits[i];
                }
            }
            if (digits.length > 0) {
                const targetIdx = Math.min(digits.length, boxes.length - 1);
                boxes[targetIdx].focus();
            }
            syncOtp();
        });
    });

    form.addEventListener('submit', function(e) {
        syncOtp();
        if (fullInput.value.length !== 6) {
            e.preventDefault();
            alert('Please enter all 6 digits of the OTP code.');
        }
    });
});
</script>
@endsection
