<?php

namespace Tests\Unit\Models;

use App\User;
use App\Remedy;
use App\Favourite;
use App\BodySystem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemedyTest extends TestCase
{
    use RefreshDatabase;

    public function test_remedy_can_be_favourited_by_user()
    {
        $remedy = factory(Remedy::class)->create();
        $favourite = factory(Favourite::class)->create([
            'favouriteable_id' => $remedy->id,
            'favouriteable_type' => Remedy::class,
        ]);

        $this->assertCount(1, $remedy->favourites);
    }

    public function test_remedy_can_have_body_systems()
    {
        $remedy = factory(Remedy::class)->create();
        $bodySystem = factory(BodySystem::class)->create();
        $remedy->bodySystems()->attach($bodySystem);

        $this->assertCount(1, $remedy->bodySystems);
    }

    public function test_remedy_can_have_related_remedies()
    {
        $remedy = factory(Remedy::class)->create();
        $relatedRemedy = factory(Remedy::class)->create();
        $remedy->relatedRemedies()->attach($relatedRemedy);

        $this->assertCount(1, $remedy->relatedRemedies);
    }

    public function test_remedy_can_belong_to_a_user()
    {
        $user = factory(User::class)->create();
        $remedy = factory(Remedy::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $remedy->user->id);
    }

    public function test_can_check_remedy_is_user_generated()
    {
        $userGeneratedRemedy = factory(Remedy::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $remedy = factory(Remedy::class)->create();

        $this->assertTrue($userGeneratedRemedy->is_user_generated);
        $this->assertFalse($remedy->is_user_generated);
    }
}
