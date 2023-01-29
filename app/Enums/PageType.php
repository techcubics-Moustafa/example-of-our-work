<?php

namespace App\Enums;

enum PageType: string
{
    case Terms_Condition = 'terms_condition';
    case About_Us = 'about_us';
    case Privacy_Policy = 'privacy_policy';
    case Footer = 'footer';
    case Sharing_Point = 'sharing_point';
}
