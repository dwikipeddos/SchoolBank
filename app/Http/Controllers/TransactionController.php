<?php

namespace App\Http\Controllers;

use App\Enums\TransactionTypes;
use App\Http\Requests\HighestReportRequest;
use App\Http\Requests\TotalReportRequest;
use App\Http\Requests\TransactionStoreManyRequest;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Queries\TransactionQuery as QueriesTransactionQuery;
use Bavix\Wallet\External\Api\TransactionQuery;
use Bavix\Wallet\External\Api\TransactionQueryHandler;
use Bavix\Wallet\Models\Wallet;

class TransactionController extends Controller
{
    protected function getTransactionBaseMeta(): array
    {
        return [
            'employee_id' => auth()->user()->employee_id,
            'user_id' => auth()->id(),
        ];
    }

    public function totalReport(TotalReportRequest $request)
    {
        return response(
            Transaction::when($request->today, fn ($q) => $q->whereDate('created_at', today()))
                ->when($request->has('start_date'), fn ($q) => $q->whereDate('created_at', '>', $request->start_date))
                ->when($request->has('end_date'), fn ($q) => $q->whereDate('created_at', '<', $request->end_date))
                ->when($request->has('type'), fn ($q) => $q->where('type', $request->type))
                ->sum('amount')
        );
    }

    public function weeklyReport()
    {
        $transactions = Transaction::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->get();
        $transactions = Transaction::all();
        $transactions = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('l');
        });


        $counts = $transactions->map(function ($group) {
            return [
                'day' => $group->first()->created_at->format('l'),
                'withdraw' => $group->where('type', TransactionTypes::WITHDRAW->value)->count(),
                'deposit' => $group->where('type', TransactionTypes::DEPOSIT->value)->count(),
            ];
        });

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $zeroCounts = collect(array_flip($days))->map(function ($day) use ($days) {
            return [
                'day' => $days[$day],
                'withdraw' => 0,
                'deposit' => 0,
            ];
        });

        $counts = $zeroCounts->merge($counts)->sortBy(function ($count) use ($days) {
            return array_search($count['day'], $days);
        });
        return $counts;
    }

    public function activeWalletReport()
    {
        $wallets = Wallet::with('holder.student.classroom.school')->whereHas('holder.student')->get();
        $summedBalances = $wallets->groupBy(function ($wallet) {
            return optional($wallet?->holder?->student?->classroom?->school)->name;
        });
        $result = [];
        foreach ($summedBalances as $sum) {
            $result[] = [
                'school' => $sum->first()->holder?->student?->classroom?->school->name,
                'balance' => $sum->sum('balance')
            ];
        }
        return response($result);
    }

    public function highestReport(HighestReportRequest $request)
    {
        return response(
            Wallet::orderBy('balance', 'desc')
                ->with('holder.student.classroom.school')
                ->when($request->school_id, fn ($q) => $q->whereRelation('holder.student.classroom', 'school_id', $request->school_id))
                ->when($request->classroom_id, fn ($q) => $q->whereRelation('holder.student', 'classroom_id', $request->classroom_id))
                ->limit($request->limit ?? 5)->get()
        );
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

        //logs activity
        $transaction = $user->transactions()->latest()->first();
        activity()
            ->performedOn($user->transactions()->latest()->first())
            ->causedBy(auth()->user())
            ->withProperties($transaction->toArray())
            ->event('created')
            ->log('created');
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
