<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin', ['except' => []]);
    }

    public function saveImage($file, $folder = 'folder', $old = null)
    {
        $filename = md5(time()) . str_slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

        $savePath = '/files/'. $folder;

        if (!is_dir(public_path($savePath))) {
            mkdir(public_path($savePath));
        }

        $filename = '/files/'.$folder. '/' . $filename;

        Image::make($file->getRealPath())->save(public_path($filename));

        if ($old) {
            @unlink(public_path($old));
        }

        return $filename;
    }

    public function saveImageThumb($file, $slug = 'moi-nhat', $old = null)
    {
        try {
            $folder = 'truyenmoi_net_thumb';

            $filename = 'doc-truyen-tranh-' . $slug . '-' . md5(time()) . '.' . $file->getClientOriginalExtension();

            $savePath = '/' . $folder;

            if (!is_dir(public_path($savePath))) {
                mkdir(public_path($savePath));
            }

            $image = Image::make($file);

            $image->fit(176, 264);

            $filename = '/' . $folder . '/' . $filename;

            $image->save(public_path($filename));

            if ($old) {
                @unlink(public_path($old));
            }
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getTraceAsString());
            $filename = '/themes/home/images/404-avatar.png';
        }

        return $filename;
    }

    public function saveAndCropImage($path, $folder = 'products', $old = null) {
        $image = Image::make($path);

        $width = $image->width();
        $height = $image->height();

        if ($width > $height) {
            $x = $width / 2 - ($height / 2);
            $y = 0;
            $image->crop($height, $height, $x, $y);
        } else if ($width < $height) {
            $x = 0;
            $y = $height / 2 - ($width / 2);
            $image->crop($width, $width, $x, $y);
        } else {
            $x = 0;
            $y = 0;
            $image->crop($width, $width, $x, $y);
        }

        $savePath = '/files/'. $folder;

        if (!is_dir(public_path($savePath))) {
            mkdir(public_path($savePath));
        }

        $filename = md5(time()) . basename($path);

        $filename = '/files/'.$folder. '/' . $filename;

        $image->save(public_path($filename));

        if ($old) {
            @unlink(public_path($old));
        }

        return $filename;
    }
}
