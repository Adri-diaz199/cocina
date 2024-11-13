<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Http\UploadedFile;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Tag;

use Laravel\Sanctum\Sanctum;

class RecipeTest extends TestCase
{
    use RefreshDatabase, WithFaker;
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
    public function test_store()
    {
        Sanctum::actingAS(User::factory()->create());

        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $data = [
            'category_id' => $category->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->text,
            'instructions' => $this->faker->text,
            'tags' => $tag->id,
            'image' => UploadedFile::fake()->image('recipe.png'),
        ];

        $response = $this->postJson('/api/recipes/', $data);
        $response->assertStatus(Response::HTTP_CREATED);
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
    public function test_update()
    {
        Sanctum::actingAS(User::factory()->create());

        $category = Category::factory()->create();
        $recipe = Recipe::factory()->create();

        $data = [
            'category_id' => $category->id,
            'title' => 'updated title',
            'description' => 'updated description',
            'ingredients' => 'updated ingredients',
            'instructions' => 'updated instructions',
        ];

        $response = $this->putJson('/api/recipes/' . $recipe->id, $data);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('recipes', [
            'title' => 'updated title',
            'description' => 'updated description',
            'ingredients' => 'updated ingredients',
            'instructions' => 'updated instructions',
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
