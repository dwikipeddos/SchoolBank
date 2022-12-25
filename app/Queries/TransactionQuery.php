<?php

namespace App\Queries;

use App\Models\Transaction;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class TransactionQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Transaction::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('user', 'payable'),
            AllowedInclude::relationship('payable.student'),
            AllowedInclude::relationship('payable.student.classroom'),
            AllowedInclude::relationship('payable.student.classroom.school'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::callback('school_id', fn ($builder, $val) => $builder->whereRelation('payable.student', 'id', $val)),
            AllowedFilter::callback('user_id', fn ($builder, $val) => $builder->where('payable_type', User::class)->where('payable_id', $val)),
            AllowedFilter::callback('employee_id', fn ($builder, $val) => $builder->whereJsonContains('employee_id', $val)),
            AllowedFilter::callback('start_date', fn ($builder, $val) => $builder->whereDate('created_at', '>=', $val)),
            AllowedFilter::callback('end_date', fn ($builder, $val) => $builder->whereDate('created_at', '<=', $val)),

        ];
    }
}
