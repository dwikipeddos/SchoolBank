<?php

namespace App\Queries;

use App\Models\Employee;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class EmployeeQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Employee::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('user'),
            AllowedInclude::relationship('schools'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('school_id', 'schools.id'),
            AllowedFilter::partial('nip'),
        ];
    }
}
