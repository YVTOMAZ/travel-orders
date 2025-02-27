<?php

namespace Tests\Feature;

use App\Models\TravelOrder;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TravelOrderTest extends TestCase
{
    // Removido o uso do RefreshDatabase

    private function createUserAndToken()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [$user, $token];
    }

    private function createTravelOrder($user)
    {
        return TravelOrder::create([
            'user_id'       => $user->id,
            'requester'     => 'Tomaz',
            'destination'   => 'Minas Gerais',
            'departure_date'=> '2025-06-01',
            'return_date'   => '2025-06-05',
            'status'        => 'requested',
        ]);
    }

    public function test_create_travel_order()
    {
        [$user, $token] = $this->createUserAndToken();

        $payload = [
            'requester'      => 'Tomaz',
            'destination'    => 'Minas Gerais',
            'departure_date' => '2025-05-01',
            'return_date'    => '2025-05-05',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/travel-orders', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['destination' => 'Minas Gerais']);
    }

    public function test_user_cannot_update_own_order_status()
    {
        [$user, $token] = $this->createUserAndToken();
        $order = $this->createTravelOrder($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->patchJson("/api/travel-orders/{$order->id}/status", ['status' => 'approved']);

        $response->assertStatus(403)
                 ->assertJsonFragment(['error' => 'User not authorized to update their own order status']);
    }

    public function test_user_can_update_order_status()
    {
        [$user, $token] = $this->createUserAndToken();
        $order = $this->createTravelOrder($user);

        $gerente = User::factory()->create();
        $gerenteToken = JWTAuth::fromUser($gerente);

        $response = $this->withHeader('Authorization', "Bearer $gerenteToken")
                         ->patchJson("/api/travel-orders/{$order->id}/status", ['status' => 'approved']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'approved']);
    }
}