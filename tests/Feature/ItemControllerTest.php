<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function it_stores_an_item(): void
    {
        $this->post('/api/items', [
            'name' => 'Tes Item',
            'slug' => 'tes-item-slug',
            'image' => UploadedFile::fake()->image('item.jpg'),
            'description' => 'description of the item',
            'starting_price' => 10000,
            'start_time' => '2024-07-08 08:30',
            'end_time' => '2024-07-09 08:30'
        ])->assertSuccessful();

        $this->assertDatabaseCount('items', 1);
    }
}
