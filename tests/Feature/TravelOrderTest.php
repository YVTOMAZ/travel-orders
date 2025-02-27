<?php

namespace Tests\Feature;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_travel_order()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

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
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = TravelOrder::create([
            'user_id'       => $user->id,
            'requester'     => 'Tomaz',
            'destination'   => 'Minas Gerais',
            'departure_date'=> '2025-06-01',
            'return_date'   => '2025-06-05',
            'status'        => 'requested',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->patchJson("/api/travel-orders/{$order->id}/status", ['status' => 'approved']);

        $response->assertStatus(403)
                 ->assertJsonFragment(['error' => 'User not authorized to update their own order status']);
    }

}
