<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreManyRequest;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\User;
use Bavix\Wallet\External\Api\TransactionQuery;
use Bavix\Wallet\External\Api\TransactionQueryHandler;
use Illuminate\Http\Request;
use Bavix\Wallet\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        return response(Transaction::all());
    }

    public function store(TransactionStoreRequest $request, User $user)
    {
        $user->deposit($request->amount);
        return response(['message' => 'ok']);
    }

    public function storeMultiple(TransactionStoreManyRequest $request)
    {
        $wallets = User::whereIn($request->only('user_ids'))->with('wallet')->pluck('wallet')->get();
        app(TransactionQueryHandler::class)->apply(
            array_map(
                static fn ($wallet) => TransactionQuery::createDeposit($wallet, $request->amount, []),
                $wallets
            )
        );
    }
}
