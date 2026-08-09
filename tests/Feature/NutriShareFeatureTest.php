<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class NutriShareFeatureTest extends TestCase
{
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_forgot_password_page_renders_successfully(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }
}
