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
    <!-- Breadcrumb & Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small text-muted">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-apple-accent"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Trust & Peer Reviews</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0" style="color: var(--apple-text);">
                <i class="bi bi-shield-check text-apple-accent me-2"></i> Trust Profile & Reviews
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
                            @elseif($user->isAdmin())
                                <span class="badge badge-admin"><i class="bi bi-shield-lock"></i> Admin</span>
                            @elseif($user->isModerator())
                                <span class="badge badge-moderator"><i class="bi bi-shield-check"></i> Moderator</span>
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
                    <i class="bi bi-chat-square-quote text-apple-accent me-2"></i> Verified Community Reviews
                </h5>
                <span class="badge border px-3 py-1" style="background: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-weight: 500;">
                    Showing {{ $reviews->count() }} of {{ $totalCount }}
                </span>
            </div>

            @forelse($reviews as $review)
                <div class="card review-card mb-3 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $review->reviewer->profile_photo_url }}" alt="{{ $review->reviewer->name }}" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover; border: 2px solid var(--apple-border);">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0" style="color: var(--apple-text);">{{ $review->reviewer->name }}</h6>
                                        @if($review->reviewer->isDonor())
                                            <span class="badge badge-donor" style="font-size: 0.65rem;">Donor</span>
                                        @elseif($review->reviewer->isNgo())
                                            <span class="badge badge-ngo" style="font-size: 0.65rem;">NGO</span>
                                        @elseif($review->reviewer->isAdmin())
                                            <span class="badge badge-admin" style="font-size: 0.65rem;">Admin</span>
                                        @endif
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ $review->created_at->format('d M Y, h:i A') }} ({{ $review->created_at->diffForHumans() }})
                                    </small>
                                </div>
                            </div>
                            <div class="text-warning fs-6 bg-warning-subtle px-3 py-1 rounded-pill" style="border: 1px solid rgba(255, 193, 7, 0.3);">
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= $review->rating)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star text-muted opacity-25"></i>
                                    @endif
                                @endfor
                                <span class="fw-bold ms-1 text-dark">{{ $review->rating }}.0</span>
                            </div>
                        </div>

                        @if($review->comment)
                            <div class="p-3 rounded-3 mb-1" style="background-color: var(--apple-input-bg); border-left: 4px solid var(--apple-accent);">
                                <p class="mb-0 text-break" style="color: var(--apple-text); font-size: 0.92rem; line-height: 1.5;">
                                    "{{ $review->comment }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card shadow-sm text-center py-5" style="border-radius: 18px; background: var(--apple-card-bg); border: 1px solid var(--apple-border);">
                    <div class="card-body py-5">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(255, 193, 7, 0.12); color: #ffc107;">
                                <i class="bi bi-star fs-1"></i>
                            </span>
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
</div>
@endsection
