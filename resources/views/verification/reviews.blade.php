@extends('layouts.app')
@section('title', 'Reviews for ' . $user->name)

@section('content')
<h2 class="mb-4"><i class="bi bi-star"></i> Reviews for {{ $user->name }}</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <h1 class="display-4 text-warning">{{ number_format($user->averageRating(), 1) }}</h1>
                <p class="text-muted">Average Rating</p>
                <p><small>{{ $user->reviewsReceived()->count() }} reviews</small></p>
            </div>
        </div>

        @auth
        @if(Auth::id() !== $user->id)
        <div class="card">
            <div class="card-header">Write a Review</div>
            <div class="card-body">
                <form method="POST" action="{{ route('reviews.submit', $user) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} ⭐</option>
                            @endfor
                        </select>
                        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" placeholder="Your review..." required>{{ old('comment') }}</textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-ns-primary btn-sm w-100">Submit Review</button>
                </form>
            </div>
        </div>
        @endif
        @endauth
    </div>

    <div class="col-md-8">
        @forelse($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <strong>{{ $review->reviewer->name }}</strong>
                    <span class="text-warning">{{ str_repeat('⭐', $review->rating) }}</span>
                </div>
                @if($review->comment)
                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                @endif
                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @empty
        <p class="text-muted">No reviews yet.</p>
        @endforelse

        {{ $reviews->links() }}
    </div>
</div>
@endsection
