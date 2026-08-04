<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Mail\ResetPasswordOtpMail;

/**
 * PasswordResetController — OTP-based password reset flow.
 *
 * Flow:
 * 1. User enters email → OTP generated & sent (or shown in local dev mode)
 * 2. User enters 6-digit OTP → verified, token generated
 * 3. User enters new password → password reset using token
 */
class PasswordResetController extends Controller
{
    /**
     * Step 1: Show the form to request a password reset OTP.
     */
    public function requestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Step 1: Generate OTP and send it (or display in local dev mode).
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        // Invalidate any previous OTPs for this email
        PasswordResetOtp::where('email', $request->email)->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otpRecord = PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);
        // Send OTP email via Mailpit (local SMTP on port 1025)
        try {
            Mail::to($request->email)->send(new ResetPasswordOtpMail($otp, $request->email));
        } catch (\Exception $e) {
            // If Mailpit is not running, fallback to showing OTP on screen
            return redirect()->route('password.otp.form', ['email' => $request->email])
                ->with('status', "Your OTP code is: {$otp} (Mailpit not running — showing OTP directly, valid for 10 minutes)");
        }

        return redirect()->route('password.otp.form', ['email' => $request->email])
            ->with('status', 'We have sent a 6-digit OTP code to your email. Check Mailpit at http://127.0.0.1:8025');
    }

    /**
     * Step 2: Show the OTP verification form.
     */
    public function otpForm(Request $request)
    {
        $email = $request->query('email', $request->old('email'));

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Email is required.']);
        }

        return view('auth.passwords.otp', compact('email'));
    }

    /**
     * Step 2: Verify the OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('verified_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please try again.'])->withInput();
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'This OTP has expired. Please request a new one.'])->withInput();
        }

        // Mark as verified and generate a secure token for the reset form
        $token = Str::random(64);
        $otpRecord->update([
            'verified_at' => now(),
            'token' => $token,
        ]);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Step 2 (resend): Resend OTP code.
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Re-use the sendOtp logic
        return $this->sendOtp($request);
    }

    /**
     * Step 3: Show the password reset form.
     */
    public function resetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Step 3: Reset the password using the verified token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ]);

        // Verify the token is valid and verified
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('token', $request->token)
            ->whereNotNull('verified_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset token. Please start the process again.']);
        }

        // Reset the password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));

        $user->save();

        // Clean up all OTP records for this email
        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully! Please login with your new password.');
    }
}
