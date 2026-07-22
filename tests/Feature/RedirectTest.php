<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    public function test_admin_visiting_guardian_login_is_not_redirected_to_admin_dashboard(): void
    {
        $user = new User(['id' => 1]);

        $response = $this->actingAs($user, 'web')
            ->get('/guardian/login');

        // It should return the view for the guardian login page, 
        // which means status 200, not a 302 redirect.
        $response->assertStatus(200);
    }
}
