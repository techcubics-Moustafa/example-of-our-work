<?php

namespace App\Traits\Helper;


use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

trait UploadFileTrait
{
    public function storeFile($file, $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|File
    {
        $data = new Fluent($data);
        $hashName = $file->hashName();
        $fullFile = $data->path . "/{$hashName}";
        $file->store($data->path);
        return File::query()->create([
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getType(),
            'file' => $hashName,
            'full_file' => $fullFile,
            'path' => $data->path,
            'relationable_type' => $data->relationable_type,
            'relationable_id' => $data->relationable_id,
            'column_name' => $data->column_name,
        ]);
    }

    public function uploadAsName($data, $nameFileNew)
    {
        if ($data['upload_type'] == 'single') {
            $file = \request()->hasFile($data['file']);
            if ($file) {
                Storage::has($data['delete_file']) ? Storage::delete($data['delete_file']) : '';
                \request()->file($data['file'])->storeAs($data['path'], $nameFileNew);
                return true;
            }
        }
    }

    public function upload($data = [])
    {

        if (\request()->hasFile($data['file']) && $data['upload_type'] == 'single') {
            Storage::has($data['delete_file']) ? Storage::delete($data['delete_file']) : '';
            return \request()->file($data['file'])->store($data['path']);
        } elseif (\request()->hasFile($data['file']) && $data['upload_type'] == 'files' && $data['multi_upload'] == null) {

            $file = \request()->file($data['file']);
            return $this->storeFile($file, $data);
        } elseif (\request()->hasFile($data['file']) && $data['upload_type'] == 'files' && $data['multi_upload'] == true) {
            $files = \request()->file($data['file']);

            foreach ($files as $file) {
                $this->storeFile($file, $data);
            }
        }
    }

    public static function uploadSingleFile($data = [])
    {
        if (\request()->hasFile($data['file']) && $data['upload_type'] == 'single') {
            Storage::has($data['delete_file']) ? Storage::delete($data['delete_file']) : '';
            return \request()->file($data['file'])->store($data['path']);
        }
    }

    public function uploadImageBase64($data = []): string
    {
        $image_64 = $data['file_name']; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = Str::random(10) . '.' . $extension;

        $path = $data['path'] . '/' . $imageName;

        Storage::put($path, base64_decode($image));
        return $path;
    }

    public function deleteFile($path): void
    {
        if (!is_null($path)) {
            Storage::has($path) ? Storage::delete($path) : '';
        }
    }

}
