<?php

namespace App\Policies;

use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create-transaction');
    }

    public function viewAny(User $user)
    {
        return $user->can('view-any-transaction');
    }

    public function edit(User $user, Transaction $transaction)
    {
        return true;
    }
}
