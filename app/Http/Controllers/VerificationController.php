<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationDocument;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * VerificationController — Module 2: NGO Verification & Trust Management (Cheon Jie Han)
 */
class VerificationController extends Controller
{
    /** Show pending NGO verifications (Admin view). */
    public function index()
    {
        $documents = VerificationDocument::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('verification.index', compact('documents'));
    }

    /** Upload verification document (NGO). */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:license,registration_cert,tax_exempt',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('document')->store('verification_documents', 'public');

        VerificationDocument::create([
            'user_id' => Auth::id(),
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $request->file('document')->getClientOriginalName(),
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Document uploaded successfully. Please wait for admin verification.');
    }

    /** Approve or reject a verification document (Admin). */
    public function review(Request $request, VerificationDocument $document)
    {
        $validated = $request->validate([
            'action' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $document->update([
            'status' => $validated['action'],
            'admin_remarks' => $validated['admin_remarks'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // If approved, update the NGO user's verification status
        if ($validated['action'] === 'approved') {
            $document->user->update(['verification_status' => 'approved']);
        }

        return redirect()->route('verification.index')
            ->with('success', "Document {$validated['action']} successfully.");
    }

    /** Show reviews/trust page. */
    public function reviews(User $user)
    {
        $reviews = $user->reviewsReceived()->with('reviewer')->latest()->paginate(10);
        return view('verification.reviews', compact('user', 'reviews'));
    }

    /** Submit a review. */
    public function submitReview(Request $request, User $user)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::updateOrCreate(
            ['reviewer_id' => Auth::id(), 'reviewee_id' => $user->id],
            $validated
        );

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

    /** View verification document inline. */
    public function showFile(VerificationDocument $document)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isModerator() && $user->id !== $document->user_id) {
            abort(403, 'Unauthorized access.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->response($document->file_path);
    }

    /** Download verification document. */
    public function download(VerificationDocument $document)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isModerator() && $user->id !== $document->user_id) {
            abort(403, 'Unauthorized access.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($document->file_path, $document->original_filename);
    }
}
