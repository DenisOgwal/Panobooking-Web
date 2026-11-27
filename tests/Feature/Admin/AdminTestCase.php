<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function admin(): User
    {
        return User::where('email', 'admin@panobooking.test')->firstOrFail();
    }

    protected function staff(): User
    {
        return User::where('email', 'staff@panobooking.test')->firstOrFail();
    }

    protected function asAdmin(): static
    {
        return $this->actingAs($this->admin());
    }

    protected function asStaff(): static
    {
        return $this->actingAs($this->staff());
    }
}
