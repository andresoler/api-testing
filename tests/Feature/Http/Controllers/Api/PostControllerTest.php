<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {

        //Visualiza claramente lo que se ejecuta
        //$this->withoutExceptionHandling();

        $response = $this->json('POST','/api/posts', [
            'title' => 'El post de prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
            ->assertJson(['title' => 'El post de prueba'])
            ->assertStatus(201);

        $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']);
    }

    public function test_validate_title()
    {


        $response = $this->json('POST','/api/posts', [
            'title' => ''
        ]);

        // Stattus imposible de validar
        //Validar que no se reciba un titulo nulo
        $response->assertStatus(422)
            ->assertJsonValidationErrors('title');

    }

    public function test_show()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('GET',"/api/posts/$post->id"); // id = 1

        $response->assertJsonStructure(['id','title','created_at','updated_at'])
        ->assertJson(['title' => $post->title])
        ->assertStatus(200);

    }

    public function test_404_show()
    {

        $response = $this->json('GET',"/api/posts/1000");

        $response->assertStatus(404);

    }



}
