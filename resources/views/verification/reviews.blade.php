@extends('layouts.app')
@section('title', 'Trust & Reviews — ' . $user->name)

@push('styles')
<style>
    .trust-score-hero {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(41, 151, 255, 0.1) 100%);
        border: 1px solid rgba(255, 193, 7, 0.3) !important;
        border-radius: 20px;
    }
    .star-bar-fill {
        background: linear-gradient(90deg, #ffc107, #ff9500);
        border-radius: 10px;
        height: 8px;
    }
    .star-rating-select .star-option {
        cursor: pointer;
        font-size: 1.5rem;
        color: var(--apple-border);
        transition: color 0.2s ease, transform 0.2s ease;
    }
    .star-rating-select .star-option:hover,
    .star-rating-select .star-option.selected {
        color: #ffc107;
        transform: scale(1.15);
    }
    .review-card {
        border-radius: 16px;
        border: 1px solid var(--apple-border) !important;
        background: var(--apple-card-bg);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
@if($user->isAdmin() || $user->isModerator())
    <!-- Header Section for Governance Profile -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--apple-text);">
                <i class="bi bi-shield-check text-apple-accent me-2"></i> 
                {{ $user->isAdmin() ? 'Platform Governance Profile' : 'Compliance Oversight Profile' }} — {{ $user->name }}
            </h2>
            <p class="text-muted small mb-0 mt-1">Official system governance, statutory verification & platform security record</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url()->previous() ?: route('dashboard') }}" class="btn btn-outline-secondary btn-sm" style="border-color: var(--apple-border); color: var(--apple-text); border-radius: 980px; padding: 6px 16px;">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- User Profile Header Card -->
    <div class="card mb-4 shadow-sm" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm" style="width: 72px; height: 72px; object-fit: cover; border: 3px solid var(--apple-accent);">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fw-bold mb-0" style="color: var(--apple-text);">{{ $user->name }}</h3>
                            @if($user->isAdmin())
                                <span class="badge badge-admin"><i class="bi bi-shield-lock me-1"></i> System Administrator</span>
                            @else
                                <span class="badge badge-moderator"><i class="bi bi-shield-check me-1"></i> Compliance Moderator</span>
                            @endif
                            <span class="badge" style="background: rgba(41, 151, 255, 0.12); color: #2997ff; border: 1px solid rgba(41, 151, 255, 0.25); border-radius: 980px;">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified Platform Authority
                            </span>
                        </div>
                        <p class="mb-1 text-muted fw-medium mt-1" style="font-size: 0.95rem;">
                            <i class="bi bi-building me-1"></i> NutriShare Trust & Governance Secretariat
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="bi bi-shield-shaded me-1"></i> Platform Oversight Officer &bull; Member since {{ $user->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 text-end align-items-center">
                    <div class="px-3 py-2 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--apple-border);">
                        <div class="text-apple-accent fw-bold fs-6 mb-0">Impartial Arbiter</div>
                        <small class="text-muted extra-small">Governance Mandate</small>
                    </div>
                    <div class="px-3 py-2 rounded-3 text-center" style="background: rgba(52, 199, 89, 0.08); border: 1px solid rgba(52, 199, 89, 0.25);">
                        <div class="text-apple-success fw-bold fs-6 mb-0">Active & Compliant</div>
                        <small class="text-muted extra-small">Security Status</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Governance Details Grid -->
    <div class="row g-4">
        <div class="col-lg-5">
            <!-- Platform Neutrality & Policy Card -->
            <div class="card mb-4 shadow-sm" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
                <div class="card-header bg-transparent py-3" style="border-bottom: 1px solid var(--apple-border);">
                    <h5 class="fw-bold mb-0" style="color: var(--apple-text);">
                        <i class="bi bi-shield-shaded text-apple-accent me-2"></i> Platform Governance Role
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert border-0 rounded-4 mb-4" style="background: rgba(41, 151, 255, 0.1); color: var(--apple-text); border: 1px solid rgba(41, 151, 255, 0.25) !important;">
                        <i class="bi bi-info-circle-fill text-apple-accent me-2"></i>
                        <strong>Peer Review Exemption:</strong> Platform administrators and compliance moderators serve as neutral system overseers. They do not participate in food exchange transactions, ensuring impartial dispute adjudication and statutory compliance.
                    </div>

                    <h6 class="fw-bold mb-3" style="color: var(--apple-text);">Core Oversight Responsibilities</h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(52, 199, 89, 0.12); color: #34c759;">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div>
                                <strong class="small d-block" style="color: var(--apple-text);">NGO Legal & Hygiene Verification</strong>
                                <span class="extra-small text-muted">Validates Registrar of Societies (ROS) certificates, LHDN tax exemptions, and health inspection permits.</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(41, 151, 255, 0.12); color: #2997ff;">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <strong class="small d-block" style="color: var(--apple-text);">Platform Security & Data Privacy</strong>
                                <span class="extra-small text-muted">Protects user privacy, enforces role-based access control (RBAC), and prevents unauthorized tampering.</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(255, 159, 10, 0.12); color: #ff9f0a;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <strong class="small d-block" style="color: var(--apple-text);">Immutable Activity Audit Trails</strong>
                                <span class="extra-small text-muted">Maintains cryptographic activity logs for all food donations, claims, temperature checks, and distributions.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <!-- Platform Code of Conduct & Quick Actions -->
            <div class="card mb-4 shadow-sm" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
                <div class="card-header bg-transparent py-3" style="border-bottom: 1px solid var(--apple-border);">
                    <h5 class="fw-bold mb-0" style="color: var(--apple-text);">
                        <i class="bi bi-globe2 text-apple-success me-2"></i> UN SDG 2: Zero Hunger Governance Charter
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        NutriShare operates under a strict food redistribution governance protocol ensuring all surplus donations diverted from landfills to vulnerable families meet high food safety, cold-chain preservation, and traceability standards.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--apple-border);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-thermometer-snow text-info"></i>
                                    <strong class="small" style="color: var(--apple-text);">Cold-Chain Safety</strong>
                                </div>
                                <span class="extra-small text-muted">Zero-tolerance policy for temperature excursions during perishable food logistics.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--apple-border);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-clock-history text-warning"></i>
                                    <strong class="small" style="color: var(--apple-text);">Expedited Redistribution</strong>
                                </div>
                                <span class="extra-small text-muted">Ensures food items are collected and distributed well before expiry deadlines.</span>
                            </div>
                        </div>
                    </div>

                    @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isModerator()))
                    <div class="pt-3 border-top" style="border-color: var(--apple-border) !important;">
                        <h6 class="fw-bold mb-3" style="color: var(--apple-text);"><i class="bi bi-lightning-charge text-warning me-1"></i> Governance Quick Actions</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('verification.index') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-2" style="border-radius: 980px;">
                                <i class="bi bi-patch-check"></i> NGO Verification Queue
                            </a>
                            <a href="{{ route('logs.index') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2" style="border-radius: 980px;">
                                <i class="bi bi-journal-text"></i> System Audit Logs
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2" style="border-radius: 980px; border-color: var(--apple-border); color: var(--apple-text);">
                                <i class="bi bi-graph-up"></i> Analytics & Telemetry
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Donor & NGO Peer Reviews Section -->
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--apple-text);">
                <i class="bi bi-star-fill text-warning me-2"></i> Reviews for {{ $user->name }}
            </h2>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url()->previous() ?: route('dashboard') }}" class="btn btn-outline-secondary btn-sm" style="border-color: var(--apple-border); color: var(--apple-text);">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- User Profile Header Card -->
    <div class="card mb-4 shadow-sm" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm" style="width: 72px; height: 72px; object-fit: cover; border: 3px solid var(--apple-accent);">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fw-bold mb-0" style="color: var(--apple-text);">{{ $user->name }}</h3>
                            @if($user->isDonor())
                                <span class="badge badge-donor"><i class="bi bi-shop"></i> Donor</span>
                            @elseif($user->isNgo())
                                <span class="badge badge-ngo"><i class="bi bi-box2-heart"></i> NGO</span>
                            @endif

                            @if($user->isNgo() && $user->verification_status === 'approved')
                                <span class="badge bg-success text-white" style="border-radius: 980px;"><i class="bi bi-patch-check-fill me-1"></i> Verified Partner</span>
                            @endif
                        </div>
                        @if($user->organization_name)
                            <p class="mb-1 text-muted fw-medium" style="font-size: 0.95rem;">
                                <i class="bi bi-building me-1"></i> {{ $user->organization_name }}
                            </p>
                        @endif
                        <p class="mb-0 small text-muted">
                            <i class="bi bi-calendar3 me-1"></i> Member since {{ $user->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>

                @php
                    $totalCount = $user->reviewsReceived()->count();
                    $avgRating = (float) $user->averageRating();
                @endphp
                <div class="text-end">
                    <div class="d-flex align-items-center gap-2 justify-content-end mb-1">
                        <span class="display-6 fw-bold text-warning mb-0" style="line-height: 1;">{{ number_format($avgRating, 1) }}</span>
                        <div>
                            <div class="text-warning fs-5" style="line-height: 1;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted d-block mt-1">{{ $totalCount }} {{ Str::plural('Review', $totalCount) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Rating Breakdown & Write Review Form -->
        <div class="col-lg-4">
            <!-- Overall Rating Score Card -->
            <div class="card mb-4 shadow-sm trust-score-hero">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3" style="color: var(--apple-text);"><i class="bi bi-award-fill text-warning me-1"></i> Platform Trust Score</h5>
                    <div class="display-3 fw-bold text-warning mb-1" style="letter-spacing: -0.03em;">{{ number_format($avgRating, 1) }}</div>
                    <div class="text-warning fs-4 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <i class="bi bi-star-fill"></i>
                            @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star opacity-50"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="small text-muted mb-4">Based on {{ $totalCount }} community feedback {{ Str::plural('rating', $totalCount) }}</p>

                    <!-- Rating Distribution Progress Bars -->
                    <div class="text-start">
                        @for($star = 5; $star >= 1; $star--)
                            @php
                                $starCount = $user->reviewsReceived()->where('rating', $star)->count();
                                $percentage = $totalCount > 0 ? round(($starCount / $totalCount) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-2 small">
                                <span class="me-2 text-warning fw-medium" style="width: 35px;">{{ $star }} <i class="bi bi-star-fill" style="font-size: 0.75rem;"></i></span>
                                <div class="progress flex-grow-1 me-3" style="height: 8px; background-color: var(--apple-input-bg); border-radius: 10px;">
                                    <div class="progress-bar star-bar-fill" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="text-muted" style="width: 38px; text-align: right;">{{ $starCount }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Write a Review Form Card -->
            @auth
                @if(Auth::id() !== $user->id)
                <div class="card shadow-sm mb-4" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
                    <div class="card-header bg-transparent py-3" style="border-bottom: 1px solid var(--apple-border);">
                        <h5 class="fw-bold mb-0" style="color: var(--apple-text);"><i class="bi bi-pencil-square text-apple-accent me-2"></i> Submit Peer Review</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('reviews.submit', $user) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small" style="color: var(--apple-text);">Rating Score</label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror" required style="border-radius: 10px;">
                                    <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5/5) — Excellent Collaboration</option>
                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4/5) — Good & Reliable</option>
                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ (3/5) — Satisfactory</option>
                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ (2/5) — Needs Improvement</option>
                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ (1/5) — Unsatisfactory</option>
                                </select>
                                @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small" style="color: var(--apple-text);">Feedback & Comments</label>
                                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Share your experience working with {{ $user->name }} regarding food claims, pickup punctuality, or handling quality..." required style="border-radius: 12px; font-size: 0.9rem;">{{ old('comment') }}</textarea>
                                @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-ns-primary w-100 py-2" style="border-radius: 980px;">
                                <i class="bi bi-send-fill me-1"></i> Submit Official Review
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4" style="background: rgba(41, 151, 255, 0.1); color: var(--apple-text); border: 1px solid rgba(41, 151, 255, 0.25) !important;">
                    <i class="bi bi-info-circle-fill text-apple-accent me-2"></i> This is your own public profile. Other platform users can view your trust score and leave peer feedback here.
                </div>
                @endif
            @endauth
        </div>

        <!-- Right Column: Reviews Feed & List -->
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0" style="color: var(--apple-text);">
                    <i class="bi bi-chat-left-quote-fill text-apple-accent me-2"></i> Verified Community Reviews
                </h5>
                <span class="badge bg-secondary rounded-pill">Showing {{ $reviews->count() }} of {{ $totalCount }}</span>
            </div>

            @forelse($reviews as $review)
                <div class="card review-card mb-3 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $review->reviewer->profile_photo_url }}" alt="{{ $review->reviewer->name }}" class="rounded-circle shadow-sm" style="width: 44px; height: 44px; object-fit: cover;">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0" style="color: var(--apple-text);">{{ $review->reviewer->name }}</h6>
                                        @if($review->reviewer->isDonor())
                                            <span class="badge badge-donor" style="font-size: 0.68rem;"><i class="bi bi-shop"></i> Donor</span>
                                        @elseif($review->reviewer->isNgo())
                                            <span class="badge badge-ngo" style="font-size: 0.68rem;"><i class="bi bi-box2-heart"></i> NGO</span>
                                        @endif
                                    </div>
                                    @if($review->reviewer->organization_name)
                                        <small class="text-muted">{{ $review->reviewer->organization_name }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-warning fs-6 mb-1">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="bi {{ $s <= $review->rating ? 'bi-star-fill' : 'bi-star text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted extra-small">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mb-0 mt-3" style="color: var(--apple-text); line-height: 1.5; font-size: 0.95rem;">
                            {{ $review->comment }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px dashed var(--apple-border) !important;">
                    <div class="card-body">
                        <div class="display-4 text-warning mb-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--apple-text);">No Reviews Received Yet</h4>
                        <p class="text-muted mx-auto mb-4" style="max-width: 450px; font-size: 0.92rem;">
                            {{ $user->name }} has not received any community peer reviews yet. Once food pickup collections are completed, platform members can leave ratings here.
                        </p>
                        @auth
                            @if(Auth::id() !== $user->id)
                                <a href="#review" onclick="document.querySelector('textarea[name=\'comment\']').focus()" class="btn btn-ns-primary px-4" style="border-radius: 980px;">
                                    <i class="bi bi-pencil-square me-1"></i> Write the First Review
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endif
</div>
@endsection
