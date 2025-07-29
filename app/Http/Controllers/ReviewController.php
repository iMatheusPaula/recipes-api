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
        try {
            $data = $request->validated();

            $recipe->reviews()->create([
                ...$data,
                'ip_address' => $request->ip()
            ]);

            return response()->json('Avaliação criada com sucesso.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
