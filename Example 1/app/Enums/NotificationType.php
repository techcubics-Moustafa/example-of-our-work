<?php

namespace App\Enums;

enum NotificationType: string
{
    case All = 'all';
    case Customer = 'customer';
    case Clinic = 'clinic';
}
