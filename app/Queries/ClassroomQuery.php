<?php

namespace App\Queries;

use App\Models\Classroom;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ClassroomQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Classroom::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('school'),
            AllowedInclude::relationship('students'),
            AllowedInclude::relationship('students.user'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('school_id'),
            AllowedFilter::exact('year'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('class'),
        ];
    }
}
