<?php

namespace App\Helpers\CPU;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Feature;
use App\Models\File;
use App\Models\Governorate;
use App\Models\Service;
use App\Models\Project;
use App\Models\Region;
use App\Models\Special;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Models
{

    public static function countries($status = Status::Active)
    {
        return Country::query()
            ->orderByDesc('created_at')
            ->status($status)
            ->get();
    }

    public static function governorates($countryId, $status = Status::Active)
    {
        return Governorate::query()
            ->orderByDesc('created_at')
            ->whereCountryId($countryId)
            ->status($status)
            ->get();
    }

    public static function regions($governorateId, $status = Status::Active)
    {
        return Region::query()
            ->orderByDesc('created_at')
            ->whereGovernorateId($governorateId)
            ->status($status)
            ->get();
    }

    public static function users($userType = UserType::Individual, $status = Status::Active, $blocked = Status::Not_Active)
    {
        return User::query()
            ->orderByDesc('created_at')
            ->userType($userType)
            ->status($status)
            ->blocked($blocked)
            ->get();
    }

    public static function projects(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Project::query()->with(['realEstate'])->orderByDesc('created_at')->get();
    }

    public static function features($status = Status::Active)
    {
        return Feature::query()->orderByDesc('created_at')->status($status)->get();
    }

    public static function categories($status = Status::Active)
    {
        return Category::query()
            ->orderByDesc('created_at')
            ->whereNull('parent_id')
            ->status($status)
            ->get();
    }

    public static function subCategories($parent, $status = Status::Active)
    {
        return Category::query()
            ->orderByDesc('created_at')
            ->where('parent_id', '=', $parent)
            ->status($status)
            ->get();
    }

    public static function companies($userId, $status = Status::Active)
    {
        return Company::query()
            ->orderByDesc('created_at')
            ->where('user_id', '=', $userId)
            ->status($status)
            ->get();
    }

    public static function currencies($status = Status::Active)
    {
        return Currency::query()->orderByDesc('created_at')->status($status)->get();
    }

    public static function special($status = Status::Active)
    {
        return Special::query()->orderByDesc('created_at')->status($status)->get();
    }

    public static function services($status = Status::Active)
    {
        return Service::query()->orderByDesc('created_at')->status($status)->get();
    }

    public static function qrCodeGenerator($generateName, $storePath): void
    {
        /*$userId = 1; //Crypt::encrypt(12);

        $qr = QrCode::format('png')
            ->eye('circle')
            ->gradient(0, 0, 0, 0, 0, 0, 'vertical')
            ->merge('/public/assets/images/no-image.png', .3)
            ->generate($userId);
        Storage::disk('public')->put('merge_images/' . $userId . '.png', $qr);*/

        //Crypt::encryptString(12);
        //dd($userId);
        //\Artisan::call('storage:link');
        //dd(Crypt::decrypt($userId));
        $qr = QrCode::format('png')
            //->size(100)
            //->mergeString($mergePath)
            ->size(284, 284)
            //->color(255, 255, 255)
            //->style('dot')
            ->eye('square')
            //->eyeColor(255,255,255,255,255,255,255)
            //->backgroundColor(255, 255, 255)
            //->backgroundColor(10,14,244)
            //->gradient(100, 150, 200, 250, 125, 130, 'horizontal')
            //->merge($mergePath) // '/public/assets/images/no-image.png'
            ->generate($generateName);

        Storage::disk('public')->put($storePath, $qr);
    }


    public static function deleteFile(array $data)
    {
        return File::query()->where([
            'id' => $data['id'],
            'relationable_type' => $data['relationable_type'],
            'relationable_id' => $data['relationable_id'],
        ])->first();
    }

    public static function ruleImages(int $files, $count = 4): array
    {
        $rules = [];
        if ($files == 0) {
            $rules += [
                'images' => ['required', 'array', 'min:1', 'max:' . $count - $files],
                'images.*' => validationImage(true),
            ];

        } else {
            $rules += [
                'images' => ['nullable', 'array', 'min:1', 'max:' . $count - $files],
                'images.*' => validationImage(),
            ];
        }
        return $rules;
    }

    public static function country($id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Country::query()->find($id);
    }

}
