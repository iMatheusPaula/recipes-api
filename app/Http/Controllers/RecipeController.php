<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Exception;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes.
     */
    public function index(): JsonResponse
    {
        $recipes = Recipe::query()
            ->with(['user', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($recipes);
    }

    /**
     * Store a newly created recipe
     *
     * @param CreateRecipeRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(CreateRecipeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $recipe = Recipe::query()->create([
                ...$data,
                'user_id' => auth()->id(),
            ]);

            return response()->json($recipe, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified recipe.
     */
    public function show(Recipe $recipe): JsonResponse
    {
        try {
            $recipe->load(['user', 'reviews']);

            return response()->json($recipe);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified recipe in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    {
        try {
            $data = $request->validated();

            $recipe->update($data);

            return response()->json($recipe);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified recipe from storage.
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        try {
            $recipe->delete();

            return response()->json(['message' => 'Recipe deleted successfully'], 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
