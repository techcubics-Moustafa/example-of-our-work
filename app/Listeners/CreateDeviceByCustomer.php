<?php

namespace App\Listeners;

use App\Models\Setting;
use donatj\UserAgent\UserAgentParser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CreateDeviceByCustomer
{
    public function handle($event): void
    {
        $parser = new UserAgentParser();
        $ua = $parser->parse();
        $platform = $ua->platform();
        $browser = $ua->browser();
        $browserVersion = $ua->browserVersion();
        $event->customer->devices()->updateOrCreate([
            'browser' => $browser
        ], [
            'platform' => $platform,
            'browser' => $browser,
            'browser_version' => $browserVersion,
        ]);


    }
}
