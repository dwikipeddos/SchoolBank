<?php

namespace App\Queries;

use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class RoleQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Role::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('users'),
            AllowedInclude::relationship('users.student'),
            AllowedInclude::relationship('users.employee'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
        ];
    }

    protected function getAllowedSorts(): array
    {
        return [];
    }
}
