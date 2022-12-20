<?php

namespace App\Queries;

use App\Models\Student;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class StudentQuery extends PaginatedQuery
{
    public function __construct()
    {
        parent::__construct(Student::query());
    }

    protected array $append = [
        'balance',
    ];

    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('user'),
            AllowedInclude::relationship('classroom'),
            AllowedInclude::relationship('classroom.school'),
        ];
    }

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('classroom_id'),
            AllowedFilter::exact('school_id', 'classroom.id'),
            AllowedFilter::partial('nis'),
        ];
    }
}
