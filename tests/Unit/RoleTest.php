<?php

namespace Tests\Unit;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_role_relation_existence()
    {
        $this->assertTrue(method_exists(Role::class, 'users'));
    }
}
