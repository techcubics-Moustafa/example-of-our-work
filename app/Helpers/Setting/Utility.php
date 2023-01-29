<?php

namespace App\Helpers\Setting;

use App\Events\UpdateSetting;

class Utility
{
    public function setting()
    {
        if (!file_exists(storage_path('setting/site.json'))) {
            event(new UpdateSetting());
        }
        $jsonString = file_get_contents(storage_path('setting/site.json'));
        return json_decode($jsonString, true);
    }

    public static function settings(): array
    {
        $data = new Utility();

        return $data->setting();
    }

    public static function setEnvironmentValue(array $values): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    public static function getValByName($key): string
    {
        $setting = Utility::settings();
        if (empty($setting[$key])) {
            $setting[$key] = '';
        }

        return $setting[$key];
    }
}
