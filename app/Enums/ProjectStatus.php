<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Not_Available = 'not_available';
    case Preparing_Selling = 'preparing_selling';
    case Selling = 'selling';
    case Sold = 'sold';
    case Building = 'building';
}
