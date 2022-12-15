<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreManyRequest;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\User;
use App\Queries\TransactionQuery as QueriesTransactionQuery;
use Bavix\Wallet\External\Api\TransactionQuery;
use Bavix\Wallet\External\Api\TransactionQueryHandler;
use Bavix\Wallet\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        return response((new QueriesTransactionQuery)->includes()->filterSortPaginate());
    }

    public function store(TransactionStoreRequest $request, User $user)
    {
        if ($request->amount > 0)
            $user->deposit($request->amount);
        else if ($request->amount < 0)
            $user->withdraw(abs($request->amount));
        else throw new \Exception('amount cannot be 0');
        return response(['message' => 'ok']);
    }

    public function storeMany(TransactionStoreManyRequest $request)
    {
        $wallets = User::whereIn($request->only('user_ids'))
            ->orderByRaw("FIELD(id," . implode($request->user_ids) . ")")
            ->with('wallet')
            ->get()->pluck('wallet');

        for ($i = 0; $i < count($wallets); $i++) {
            $wallets[$i]->amount = $request->amounts[$i];
        }
        app(TransactionQueryHandler::class)->apply(
            array_map(
                static fn ($wallet) => TransactionQuery::createDeposit($wallet, $request->amount, []),
                $wallets
            )
        );
    }
}
