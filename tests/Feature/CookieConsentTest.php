<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_page_is_accessible()
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
        $response->assertSee('Política de Privacidad');
    }

    public function test_authenticated_user_accepts_cookies_and_is_recorded()
    {
        $user = User::factory()->create([
            'gdpr_consent' => false,
            'privacy_policy_version' => null,
        ]);

        $this->actingAs($user)
            ->post('/cookies/accept')
            ->assertStatus(200)
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'gdpr_consent' => true,
        ]);
    }
}
