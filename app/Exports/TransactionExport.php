<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionExport implements FromCollection, WithHeadingRow
{
    public function __construct(public array $dates = [])
    {
    }

    public function collection()
    {
        $transactions = Transaction::when(isset($this->dates['start_date']), fn ($builder, $val) => $builder->whereDate('created_at', '>=', $val))
            ->when(isset($this->dates['end_date']), fn ($builder, $val) => $builder->whereDate('created_at', '<=', $val))
            ->with('payable.student.classroom.school')
            ->get();
        return $this->mapData($transactions);
    }

    public function mapData($transactions)
    {
        //do stuff
        $res = [];
        $transactions->each(function ($t) use (&$res) {
            $res[] = [
                'id' => $t->id,
                'type' => $t->type,
                'name' => $t->payable->name,
                'nis' => $t->payable->student->NIS,
                'school' => $t->payable->student->classroom->school->name,
                'class' => $t->payable->student->classroom->class . " " . $t->payable->student->classroom->name,
                'date' => $t->created_at,
                'amount' => $t->amount,
            ];
        });
        return collect($res);
    }
}
