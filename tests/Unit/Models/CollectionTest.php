<?php

namespace Tests\Unit\Models;

use App\Collection;
use Tests\TestCase;
use App\Collectable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_collection_can_have_user()
    {
        $collection = factory(Collection::class)->create();

        $this->assertNotNull($collection->user);
    }

    public function test_collection_can_have_collectables()
    {
        $collection = factory(Collection::class)->create();
        $collectable = factory(Collectable::class)->create([
            'collection_id' => $collection->id,
        ]);

        $this->assertCount(1, $collection->collectables);
    }
}
