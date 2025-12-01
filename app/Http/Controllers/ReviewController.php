<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review for a novel.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'novel_api_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'novel_api_id' => $data['novel_api_id'],
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return back()->with('success', 'Review saved!');
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, Review $review)
    {
        $this->authorizeReview($review);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $review->update($data);

        return back()->with('success', 'Review updated!');
    }

    /**
     * Delete review.
     */
    public function destroy(Review $review)
    {
        $this->authorizeReview($review);

        $review->delete();

        return back()->with('success', 'Review removed.');
    }

    private function authorizeReview(Review $review): void
    {
        abort_if($review->user_id !== auth()->id(), 403);
    }
}