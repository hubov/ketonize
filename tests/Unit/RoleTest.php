<?php

namespace Tests\Unit;

use App\Models\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_users_role_relation_existence()
    {
        $this->assertTrue(method_exists(Role::class, 'users'));
    }
}
