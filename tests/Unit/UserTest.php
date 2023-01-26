<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function roles_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'roles'));
    }

    /** @test */
    public function profile_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'profile'));
    }

    /** @test */
    public function userDiet_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'userDiet'));
    }

    /** @test */
    public function dietPlans_user_relation_exists()
    {
        $this->assertTrue(method_exists(User::class, 'dietPlans'));
    }

    /** @test */
    public function is_role_method_in_user_admin()
    {
        $user = User::factory()->hasAttached(Role::factory()->state([
            'name' => 'admin'
        ]))->create();

        $this->assertTrue($user->is('admin'));
    }

    /** @test */
    public function is_role_method_in_user_not_admin()
    {
        $user = User::factory()->hasAttached(Role::factory()->state([
            'name' => 'mod'
        ]))->create();

        $this->assertFalse($user->is('admin'));
    }
}
