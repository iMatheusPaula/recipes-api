<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Console\Command;

class GenerateRecipeReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:generate-reviews';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Gera reviews sintéticas para receitas já existentes.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipes = Recipe::all();

        if ($recipes->isEmpty()) {
            $this->warn('Nenhuma receita encontrada para gerar reviews.');

            return self::SUCCESS;
        }

        $created = 0;
        $recipes->each(function (Recipe $recipe) use (&$created) {
            Review::factory()->create([
                'recipe_id' => $recipe->id,
            ]);

            $created++;
            $this->line("Review criada para a receita {$recipe->id}");
        });

        $this->info(sprintf('%d review(s) criadas.', $created));

        return self::SUCCESS;
    }
}
