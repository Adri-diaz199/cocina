<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class RecipeTest extends TestCase
{
    use RefreshDatabase;
    public function test_index()
    {
        Sanctum::actingAS(User::factory()->create());
        Category::factory()->create();

        $recipes = Recipe::factory(2)->create();

        $response = $this->getJson('/api/recipes');
        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'attributes' => ['title', 'description', 'ingredients', 'instructions', 'image'],
                ]
            ]
        ]);
    }
    public function test_show()
    {
        Sanctum::actingAS(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response = $this->getJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => ['title', 'description', 'ingredients', 'instructions', 'image'],
            ]
        ]);
    }
    public function test_destroy()
    {
        Sanctum::actingAS(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
