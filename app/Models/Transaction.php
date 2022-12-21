<?php

namespace App\Models;

use App\Traits\LogsDefaultOptions;
use Bavix\Wallet\Models\Transaction as ModelsTransaction;

class Transaction extends ModelsTransaction
{
    use LogsDefaultOptions;
}
