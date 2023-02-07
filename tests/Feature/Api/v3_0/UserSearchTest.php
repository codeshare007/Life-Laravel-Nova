<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use App\Http\Requests\User\SearchRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\User\UserSearchController;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        
        $this->signIn();
    }

    public function test_user_can_search_for_other_users()
    {
        factory(User::class)->create(['name' => 'Ben Simon']);
        factory(User::class)->create(['name' => 'Ben David']);
        factory(User::class)->create(['name' => 'Paul Smith']);

        $this->get(action([UserSearchController::class, 'index'], ['en', 'search' => 'Ben']))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_cannot_return_more_than_10_users()
    {
        factory(User::class, 11)->create(['name' => 'Ben Simon']);

        $this->get(action([UserSearchController::class, 'index'], ['en', 'search' => 'Ben']))
            ->assertJsonCount(10, 'data');
    }

    public function test_users_are_sorted_alphabetically()
    {
        factory(User::class)->create(['name' => 'Ben Colin']);
        factory(User::class)->create(['name' => 'Ben Aaron']);
        factory(User::class)->create(['name' => 'Ben Benson']);

        $responseJson = $this->get(action([UserSearchController::class, 'index'], ['en', 'search' => 'Ben']))->json();

        $this->assertEquals('Ben Aaron', $responseJson['data'][0]['name']);
        $this->assertEquals('Ben Benson', $responseJson['data'][1]['name']);
        $this->assertEquals('Ben Colin', $responseJson['data'][2]['name']);
    }

    public function test_user_search_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(UserSearchController::class, 'index', SearchRequest::class);
    }
}
