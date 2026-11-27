<?php

namespace Tests\Feature\Api;

use App\Models\UserRegistrationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake(); // provider notifications
    }

    protected function api(string $path): string
    {
        return '/api/v1/' . ltrim($path, '/');
    }

    /** The seeded, approved demo customer. */
    protected function demo(): UserRegistrationModel
    {
        return UserRegistrationModel::where('Email', 'demo@panobooking.test')->firstOrFail();
    }

    protected function signIn(?UserRegistrationModel $customer = null): UserRegistrationModel
    {
        $customer ??= $this->demo();
        Sanctum::actingAs($customer);

        return $customer;
    }
}
