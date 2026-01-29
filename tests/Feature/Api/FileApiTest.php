<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if we can get a list of files.
     */
    public function test_can_get_list_of_files(): void
    {
        // Anda mungkin perlu membuat beberapa data file di database terlebih dahulu
        // menggunakan factory jika endpoint memerlukan data.

        $response = $this->getJson('/api/files');

        $response->assertStatus(200);
    }
}
