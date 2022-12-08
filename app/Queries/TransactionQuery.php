<?php

namespace App\Queries;

use Bavix\Wallet\Models\Transaction;

class TransactionQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Transaction::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [];
    }

    protected function getAllowedFilters(): array
    {
        return [];
    }
}
