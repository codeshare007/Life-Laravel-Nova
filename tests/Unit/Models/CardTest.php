<?php

namespace Tests\Unit\Models;

use App\Card;
use Tests\TestCase;
use App\Enums\Region;
use Tests\Traits\TestsFields;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;
    use TestsFields;

    public function test_card_has_regions()
    {
        $card = factory(Card::class)->create([
            'regions' => [
                Region::EU,
                Region::Malaysia,
            ],
        ]);

        $this->assertEquals([12, 10], $card->regions);
        $this->assertCount(2, $card->regions);
    }

    public function test_can_filter_cards_by_region()
    {
        // This test only works on MYSQL DBs, so skip by default.
        // To enable, switch test DB config in phpunit.xml
        $this->markTestSkipped();

        factory(Card::class, 2)->create([
            'regions' => [
                Region::EU,
                Region::Guatemala,
            ],
        ]);

        factory(Card::class, 2)->create([
            'regions' => [
                Region::US,
                Region::Guatemala,
            ],
        ]);

        $this->assertCount(4, Card::all());
        $this->assertCount(4, Card::forRegion(Region::Guatemala())->get());
        $this->assertCount(2, Card::forRegion(Region::EU())->get());
        $this->assertCount(2, Card::forRegion(Region::US())->get());
    }
}
