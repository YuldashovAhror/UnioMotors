<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileTrait
{
    public function photoSave($photo, $directory)
    {
        $height = Image::make($photo)->height();
        $width = Image::make($photo)->width();

        $photoPath = '/' . $directory . '/' . Str::random(10) . '.webp';

        if (file_exists(public_path($directory))){
            Image::make($photo)->encode('webp', 90)->resize($width, $height)->save(public_path($photoPath));
        }else{
            mkdir(public_path($directory), 0777, true);
            Image::make($photo)->encode('webp', 90)->resize($width, $height)->save(public_path($photoPath));
        }
        return $photoPath;
    }

    public function fileSave($video, $directory)
    {
        // dd('asd');
        $videoName = Str::random(10) . '.' . $video->getClientOriginalExtension();

        $video->move(public_path() . '/' . $directory . '/', $videoName);
        return '/' . $directory . '/' . $videoName;
    }

    public function fileDelete($model, $id, $col_name)
    {
        if (!is_null($model)){
            $model = 'App\Models' . $model;
            if (is_file(public_path($model::find($id)->$col_name))){
                unlink(public_path() . $model::find($id)->$col_name);
            }
        }else{
            if (is_file(public_path($col_name))){
                unlink(public_path() . $col_name);
            }
        }
        return back();
    }
}