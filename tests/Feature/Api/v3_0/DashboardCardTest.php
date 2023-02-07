<?php

namespace Tests\Feature\Api\v3_0;

use App\Card;
use Tests\TestCase;
use App\Enums\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\Dashboard\DashboardCardController;

class DashboardCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_cards()
    {
        // This test only works on MYSQL DBs, so skip by default.
        // To enable, switch test DB config in phpunit.xml
        $this->markTestSkipped();

        $this->signIn();

        factory(Card::class, 2)->create();

        $this->get(action([DashboardCardController::class, 'index'], 'en'))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_get_cards_in_correct_order()
    {
        // This test only works on MYSQL DBs, so skip by default.
        // To enable, switch test DB config in phpunit.xml
        $this->markTestSkipped();

        $this->signIn();

        $cardA = factory(Card::class)->create([
            'title' => 'A',
        ]);

        $cardB = factory(Card::class)->create([
            'title' => 'B',
        ]);

        Card::setNewOrder([$cardB->id, $cardA->id]);

        $responseJson = $this->get(action([DashboardCardController::class, 'index'], 'en'))->json();

        $this->assertEquals($cardB->title, $responseJson['data'][0]['title']);
        $this->assertEquals($cardA->title, $responseJson['data'][1]['title']);
    }

    public function test_can_filter_cards_by_platform()
    {
        // This test only works on MYSQL DBs, so skip by default.
        // To enable, switch test DB config in phpunit.xml
        $this->markTestSkipped();

        $this->signIn();

        factory(Card::class, 2)->create([
            'is_visible_on_ios' => true,
            'is_visible_on_android' => false,
        ]);

        factory(Card::class)->create([
            'is_visible_on_ios' => false,
            'is_visible_on_android' => true,
        ]);

        $this->get(action([DashboardCardController::class, 'index'], [
            'language' => 'en',
            'platform' => 'android',
        ]))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_cards_are_filtered_by_the_users_region()
    {
        // This test only works on MYSQL DBs, so skip by default.
        // To enable, switch test DB config in phpunit.xml
        $this->markTestSkipped();

        $this->signIn([
            'region_id' => Region::Australia,
        ]);

        factory(Card::class, 2)->create([
            'regions' => [Region::Malaysia, Region::Australia],
        ]);

        factory(Card::class)->create([
            'regions' => [Region::Malaysia],
        ]);

        $this->get(action([DashboardCardController::class, 'index'], 'en'))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_guest_cannot_get_cards()
    {
        $this->get(action([DashboardCardController::class, 'index'], 'en'))->assertStatus(401);
    }
}
