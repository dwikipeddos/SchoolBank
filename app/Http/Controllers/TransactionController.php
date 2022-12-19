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
    protected function getTransactionBaseMeta(): array
    {
        return ['employee_id' => auth()->user()->employee_id];
    }

    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        return response((new QueriesTransactionQuery)->includes()->filterSortPaginate());
    }

    public function store(TransactionStoreRequest $request, User $user)
    {
        if ($request->amount > 0)
            $user->deposit($request->amount, $this->getTransactionBaseMeta());
        else if ($request->amount < 0)
            $user->withdraw(abs($request->amount, $this->getTransactionBaseMeta()));
        else throw new \Exception('amount cannot be 0');
        return response(['message' => 'ok']);
    }

    public function storeMany(TransactionStoreManyRequest $request)
    {
        $user_ids = array_column($request->transactions, 'user_id');
        $amounts = array_column($request->transactions, 'amount');

        $wallets = User::whereIn('id', $user_ids)
            ->orderByRaw("FIELD(id," . implode($user_ids) . ")")
            ->with('wallet')
            ->get()->pluck('wallet');

        $transactions = [];
        for ($i = 0; $i < count($wallets); $i++) {
            $transactions[] = [
                'wallet' => $wallets[$i],
                'amount' => $amounts[$i],
            ];
        }
        app(TransactionQueryHandler::class)->apply(
            array_map(
                static fn ($transaction) => TransactionQuery::createDeposit($transaction['wallet'], $transaction['amount'], $this->getTransactionBaseMeta()),
                $transactions
            )
        );
    }
}
