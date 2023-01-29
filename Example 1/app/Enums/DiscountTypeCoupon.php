<?php

namespace App\Enums;

enum DiscountTypeCoupon: string
{
    case Amount = 'amount';
    case Percentage = 'percentage';
}
