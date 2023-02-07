<?php

namespace Tests\Unit\Models;

use App\User;
use App\Avatar;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_avatar_is_deleted_any_users_that_were_using_it_have_the_first_avatar_assigned()
    {
        $avatars = factory(Avatar::class, 2)->create();
        $user = factory(User::class)->create([
            'avatar_id' => $avatars[0]->id,
        ]);

        $this->assertCount(2, Avatar::all());
        $this->assertCount(1, User::all());
        $this->assertEquals($avatars[0]->id, $user->avatar->id);

        $avatars[0]->delete();

        $this->assertCount(1, Avatar::all());
        $this->assertCount(1, User::all());
        $this->assertEquals($avatars[1]->id, $user->fresh()->avatar->id);
    }
}
