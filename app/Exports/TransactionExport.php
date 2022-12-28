<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionExport implements FromCollection
{
    public function __construct(public array $dates)
    {
    }

    public function collection()
    {
        $transactions = Transaction::when($this->dates['start_date'], fn ($builder, $val) => $builder->whereDate('created_at', '>=', $val))
            ->when($this->dates['start_date'], fn ($builder, $val) => $builder->whereDate('created_at', '<=', $val))
            ->with('payable.student.classroom.school')
            ->get();
        return $this->mapData($transactions);
    }

    public function mapData($transactions)
    {
        //do stuff
        return $transactions;
    }
}
