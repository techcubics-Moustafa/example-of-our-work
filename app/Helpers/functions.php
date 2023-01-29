<?php

use App\Enums\Status;
use App\Helpers\CPU\CreateFileLanguages;
use App\Helpers\Setting\Utility;
use App\Models\Governorate;
use App\Models\Language;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

# validateImage
if (!function_exists('validationImage')) {
    function validationImage($required = false, $max = 2560, $mimes = []): array
    {
        $mimes = implode(',', array_merge(['jpg', 'jpeg', 'png', 'bmp'], $mimes));
        return [
            $required ? 'required' : 'nullable',
            'image',
            'file',
            "max:{$max}",
            "mimes:{$mimes}",
        ];
    }
}

# format date
if (!function_exists('formatDate')) {
    function formatDate($format, $date): string
    {
        return Carbon::parse($date)->format($format);
    }
}
if (!function_exists('userType')) {
    function userType(): array
    {
        $user = auth()->user();

        if ($user->userable_type == Owner::class) {
            $ownerId = $user->userable->clinics->pluck('id')->toArray();
        } else {
            $ownerId = [$user->userable->clinic_id];
        }
        return $ownerId;
    }
}

if (!function_exists('calculatePercentage')) {
    function calculatePercentage($amount, $percentage, $type): array
    {
        $price = ($amount / 100) * $percentage;
        if ($type == 'increase') {
            $total = $amount + $price;
        } else {
            $total = $amount - $price;
        }
        return [
            'amount' => $price,
            'total_amount' => round($total, 2)
        ];

    }
}

if (!function_exists('languages')) {
    function languages()
    {
        return Language::query()->status()->get();
    }
}

if (!function_exists('locale')) {
    function locale()
    {
        if (request()->expectsJson()) {
            return request('lang') ?? config('app.locale');
        }
        return config('app.locale');
    }
}

if (!function_exists('locales')) {
    function locales()
    {
        return languages()->pluck('code')->toArray();
    }
}

if (!function_exists('default_lang')) {
    function default_lang()
    {
        if (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = languages();
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $value) {
                if (array_key_exists('default', $value->toArray()) && $value->default == Status::Active->value) {
                    $code = $value->code;
                    if (array_key_exists('direction', $value->toArray())) {
                        $direction = $value->direction;
                    }
                }
            }
            session()->forget('local');
            session()->forget('direction');
            session()->put('local', $code);
            session()->put('direction', $direction);
            $lang = $code;
        }
        return $lang;

    }
}

if (!function_exists('menuRoute')) {
    function menuRoute($route, $type = 'route')
    {
        if ($type == 'route') {
            return Route::currentRouteName() == $route ? 'active' : '';
        } elseif ($type == 'lists') {
            foreach ($route as $value) {
                return Request::is($value) ? 'active' : '';
            }
        } else {
            return Request::is($route) ? 'active' : '';
        }
    }
}

if (!function_exists('_trans')) {
    function _trans($key): array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        $local = default_lang();
        App::setLocale($local);
        if (!file_exists(base_path('lang/' . $local))) {
            CreateFileLanguages::file($local);
        }
        $lang_array = include(base_path('lang/' . $local . '/message.php'));
        $processed_key = ucfirst(str_replace('_', ' ', remove_invalid_characters($key)));
        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('lang/' . $local . '/message.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('message.' . $key);
        }
        return $result;
    }
}

if (!function_exists('remove_invalid_characters')) {
    function remove_invalid_characters($str): array|string
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }
}

if (!function_exists('getAvatar')) {
    function getAvatar($path): string
    {
        return !empty($path) ? asset('storage/' . $path) : asset('assets/images/img/400x400/img2.jpg');
    }
}

if (!function_exists('getCodeTable')) {
    function getCodeTable($str, $tableName, $edit = false, $id = null): string
    {
        if ($edit) {
            $TableId = $id;
        } else {
            $table_info = DB::select("show table status like '{$tableName}'");
            $TableId = $table_info[0]->Auto_increment;
        }

        return "{$str}#" . str_pad($TableId, 1, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('getWeeks')) {
    function getWeeks($weeks): bool
    {
        $date = Carbon::now()->format('l');
        $time = now(Utility::getValByName('timezone'));
        $days = [
            'Sunday' => 1,
            'Monday' => 2,
            'Tuesday' => 3,
            'Wednesday' => 4,
            'Thursday' => 5,
            'Friday' => 6,
            'Saturday' => 7,
        ];
        $day = Arr::first($weeks, function ($value, $key) use ($date, $days) {
            $number = $days[$date];
            return $value['day_id'] == $number;
        });
        if ($day) {
            if ($time->greaterThanOrEqualTo(Carbon::parse($day['from'])) && $time->lessThanOrEqualTo(Carbon::parse($day['to']))) {
                return true;
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('tableCode')) {
    function tableCode($table): string
    {
        $table_info = DB::select("show table status like '{$table}'");
        return 'Order#' . str_pad($table_info[0]->Auto_increment, 8, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('getOwner')) {
    function getOwner($user)
    {
        if ($user->userable_type == Owner::class) {
            return $user->userable_id;
        } else {
            return $user->userable?->owner_id;
        }
    }
}

if (!function_exists('getGovernorates')) {
    function getGovernorates($country_id)
    {
        return Governorate::query()->whereCountryId($country_id)->orderByDesc('created_at')->status()->get();
    }
}

if (!function_exists('getRegions')) {
    function getRegions($governorate_id)
    {
        return Region::query()->without(['translations'])->whereGovernorateId($governorate_id)->orderByDesc('created_at')->status()->get();
    }
}

if (!function_exists('getFiles')) {
    function getFiles(array $data): \Illuminate\Database\Eloquent\Collection|array
    {
        return \App\Models\File::query()->where([
            'relationable_id' => $data['id'],
            'relationable_type' => $data['type'],
        ])->get();
    }
}

if (!function_exists('getReducedAvatar')) {
    function getReducedAvatar($base_file, $file): string
    {
        if (!Storage::exists('reduced')) {
            mkdir(storage_path('app/public/reduced'), 0777, true);
        }
        $img_path = public_path('storage/' . $base_file);
        $dest_img_path = public_path('storage/reduced/' . $file);

        if (file_exists($dest_img_path))
            return asset('storage/reduced/' . $file);

        $img = Image::make($img_path)
            ->resize(300, 300)
            ->save($dest_img_path);
        return !empty($img) ? asset('storage/reduced/' . $img->basename) : asset('assets/images/no-image.png');
    }
}

if (!function_exists('isNumbers')) {
    function isNumbers($value, $pattern = "/^\\d+$/"): string
    {
        return preg_match($pattern, $value) == 1;
    }
}


