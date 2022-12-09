<?php

namespace Tests\Feature;

use App\Traits\Testing\ActingAs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TransactionTest extends TestCase
{
   use RefreshDatabase, ActingAs;

   public function test_store_transaction()
   {
      $user = $this->createUserWithRole('school-admin');
      $this->actingAs($user);

      $response = $this->post('/api/transaction', [
         'amount' => 1000,
      ]);

      $response->assertStatus(200);
   }

   public function test_user_cannot_store_transaction_without_appropriate_role()
   {
      $user = $this->createUserWithRole('student');
      $this->actingAs($user);
      $response = $this->post('/api/transaction', [
         'amount' => 1000,
      ]);

      $response->assertStatus(Response::HTTP_UNAUTHORIZED);
   }
}
