<?php

namespace App\Enums;

enum DiscountType: string
{
    case Percentage = 'percentage';
    case Amount = 'amount';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => _trans('percentage'),
            self::Amount => _trans('amount'),
        };
    }
}
