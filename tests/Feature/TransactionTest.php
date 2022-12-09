<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\CustomTestCase;

class TransactionTest extends CustomTestCase
{
   use RefreshDatabase;

   public function test_store_transaction()
   {
      $user = $this->createUserWithRole('school-admin');
      $this->be($user);

      $response = $this->post('/api/transaction/' . $user->id, [
         'amount' => 1000,
      ]);

      $response->assertStatus(200);
   }

   public function test_user_cannot_store_transaction_without_appropriate_role()
   {
      $user = $this->createUserWithRole('student');
      $this->be($user);
      $response = $this->post('/api/transaction/' . $user->id, [
         'amount' => 1000,
      ]);

      $response->assertStatus(403);
   }
}
