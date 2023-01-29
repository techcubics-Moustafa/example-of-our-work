<?php

namespace App\Enums;

enum Period: string
{
    case Daily = 'daily';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
}
