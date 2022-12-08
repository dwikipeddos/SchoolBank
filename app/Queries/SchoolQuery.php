<?php

namespace App\Queries;

use App\Models\School;
use Spatie\QueryBuilder\AllowedInclude;

class SchoolQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(School::query());
    }

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('students', 'students.user'),
            AllowedInclude::relationship('employees', 'employees.user'),
            AllowedInclude::relationship('classrooms'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [];
    }
}
