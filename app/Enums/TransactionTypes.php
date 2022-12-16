<?php

namespace App\Enums;

enum TransactionTypes: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
}
