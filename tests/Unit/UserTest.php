<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_roles_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'roles'));
    }

    public function test_profile_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'profile'));
    }

    public function test_userDiet_user_relation_existence()
    {
        $this->assertTrue(method_exists(User::class, 'userDiet'));
    }

    public function test_is_role_method_in_user_admin()
    {
        $user = User::factory()->hasAttached(Role::factory()->state([
            'name' => 'admin'
        ]))->create();

        $this->assertTrue($user->is('admin'));
    }

    public function test_is_role_method_in_user_not_admin()
    {
        $user = User::factory()->hasAttached(Role::factory()->state([
            'name' => 'mod'
        ]))->create();

        $this->assertFalse($user->is('admin'));
    }
}
