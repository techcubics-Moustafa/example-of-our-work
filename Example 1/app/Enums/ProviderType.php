<?php

namespace App\Enums;

enum ProviderType: string
{
    case Facebook = 'facebook';
    case Google = 'google';
    case WhatsApp = 'whats_app';
}
