<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * Store a newly created review for a recipe.
     *
     * @param ReviewRequest $request
     * @param Recipe $recipe
     * @return JsonResponse
     */
    public function store(ReviewRequest $request, Recipe $recipe): JsonResponse
    {
        $data = $request->validated();

        $review = $recipe->reviews()->create([
            ...$data,
            'ip_address' => $request->ip()
        ]);

        return response()->json($review, Response::HTTP_CREATED);
    }
}
