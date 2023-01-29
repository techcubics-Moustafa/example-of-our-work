<?php

namespace App\Helpers\CPU;

use Illuminate\Support\Facades\File;

class CreateFileLanguages
{

    public static function file($code): void
    {
        $path = base_path('lang/en/message.php');
        if (!File::isDirectory($path)) {
           // File::makeDirectory($path, 0777, true, true);
            $file = fopen(base_path('lang/en/message.php'), "w") or die("Unable to open file!");
            $str = "<?php return " . var_export(['' => ''], true) . ";";
            fwrite($file, $str);
        }
        if (!file_exists(base_path('lang/' . $code))) {
            // make direction and file php
            mkdir(base_path('lang/' . $code), 0777, true);
            $lang_file = fopen(base_path('lang/' . $code . '/' . 'message.php'), "w") or die("Unable to open file!");
            $message = file_get_contents(base_path('lang/en/message.php'));
            fwrite($lang_file, $message);
            File::copy(base_path('lang/en/auth.php'), base_path('lang/' . $code . '/auth.php'));
            File::copy(base_path('lang/en/pagination.php'), base_path('lang/' . $code . '/pagination.php'));
            File::copy(base_path('lang/en/passwords.php'), base_path('lang/' . $code . '/passwords.php'));
            File::copy(base_path('lang/en/validation.php'), base_path('lang/' . $code . '/validation.php'));
        }
    }
}
