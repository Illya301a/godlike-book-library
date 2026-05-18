<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private function validBookData(): array
    {
        return [
            'title' => 'Tokyo Ghoul',
            'publisher' => 'Shueisha',
            'author' => 'Sui Ishida',
            'genre' => 'Drama',
            'publication_date' => '2011-09-08',
            'word_count' => 50000,
            'price_usd' => 10.99,
        ];
    }

    private function createBook(): int
    {
        $response = $this->postJson('/api/books', $this->validBookData());

        $response->assertCreated();

        return $response->json('id');
    }

    public function test_index_returns_empty_list(): void
    {
        $this->getJson('/api/books')
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_can_store_book(): void
    {
        $data = $this->validBookData();

        $response = $this->postJson('/api/books', $data);

        $response->assertCreated();
        $response->assertJsonFragment([
            'title' => 'Tokyo Ghoul',
            'author' => 'Sui Ishida',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Tokyo Ghoul',
            'author' => 'Sui Ishida',
        ]);
    }

    public function test_can_show_book(): void
    {
        $id = $this->createBook();

        $this->getJson('/api/books/'.$id)
            ->assertOk()
            ->assertJsonFragment(['title' => 'Tokyo Ghoul']);
    }

    public function test_can_update_book(): void
    {
        $id = $this->createBook();

        $this->patchJson('/api/books/'.$id, ['price_usd' => 7.50])
            ->assertOk()
            ->assertJsonFragment(['price_usd' => '7.50']);

        $this->assertDatabaseHas('books', [
            'id' => $id,
            'price_usd' => 7.50,
        ]);
    }

    public function test_can_destroy_book(): void
    {
        $id = $this->createBook();

        $this->deleteJson('/api/books/'.$id)
            ->assertNoContent();

        $this->getJson('/api/books/'.$id)
            ->assertNotFound();

        $this->assertDatabaseMissing('books', ['id' => $id]);
    }

    public function test_show_returns_404_when_book_not_found(): void
    {
        $this->getJson('/api/books/999')
            ->assertNotFound();
    }

    public function test_update_returns_404_when_book_not_found(): void
    {
        $this->patchJson('/api/books/999', ['title' => 'Test'])
            ->assertNotFound();
    }

    public function test_destroy_returns_404_when_book_not_found(): void
    {
        $this->deleteJson('/api/books/999')
            ->assertNotFound();
    }

    public function test_store_validation_fails_with_invalid_data(): void
    {
        $this->postJson('/api/books', [])
            ->assertUnprocessable();
    }

    public function test_update_validation_fails_with_invalid_data(): void
    {
        $id = $this->createBook();

        $this->patchJson('/api/books/'.$id, ['word_count' => -1])
            ->assertUnprocessable();
    }
}
