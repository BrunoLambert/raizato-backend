<?php

namespace App\Enums;

enum StockLogTypeEnum: string
{
    case Purchase = 'purchase';
    case Returning = 'return';
    case Sale = "sale";
    case Loss = "loss";
    case Adjustment = "adjustment";
    case Creation = 'creation';
}
