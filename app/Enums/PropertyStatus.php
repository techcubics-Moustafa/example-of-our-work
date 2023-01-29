<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Not_Available = 'not_available';
    case Preparing_Selling = 'preparing_selling';
    case Selling = 'selling';
    case Sold = 'sold';
    case Renting = 'renting';
    case Rented = 'rented';
    case Building = 'building';
}
