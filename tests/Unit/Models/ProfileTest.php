<?php

namespace Tests\Unit\Models;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_profile_relation_existence()
    {
        $this->assertTrue(method_exists(Profile::class, 'user'));
    }

    /** @test */
    public function age_method_in_profile()
    {
        $profile = new Profile;

        $profile->birthday = date("Y-m-d", strtotime("-20 years"));

        $this->assertEquals(20, $profile->age());
    }
}
