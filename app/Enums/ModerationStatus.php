<?php

namespace App\Enums;

enum ModerationStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Selling = 'selling';
    case Rejected = 'rejected';
}
