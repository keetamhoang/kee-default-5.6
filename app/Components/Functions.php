<?php

namespace App\Components;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Manga;
use Goutte\Client;

use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Shorty;
use Symfony\Component\DomCrawler\Crawler;

class Functions
{
    public static function convertSlug($string)
    {
        $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẵ|ẳ)/", 'a', $string);
        $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
        $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
        $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
        $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
        $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
        $string = preg_replace("/(đ)/", 'd', $string);
        $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
        $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
        $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
        $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
        $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
        $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
        $string = preg_replace("/(Đ)/", 'D', $string);
        $string = strtolower(trim($string));
        $string = preg_replace('/[^%a-zA-Z0-9]/', ' ', $string);
        $string = preg_replace('/\s+/', '-', $string);
        $string = preg_replace('|-+|', '-', $string);
        $parsed = trim($string, '-');

        return $parsed;
    }

    public static function filterInputNumber($num)
    {
        $num = trim($num);
        $num = str_replace('.', '', $num);
        $num = preg_replace('/\s+/', '', $num);
        return $num;
    }

    public static function saveImage($path, $filename = null, $folder = 'truyenmoi_net_thumb', $old = null) {
        try {
            $image = Image::make($path);

            $savePath = $folder;

            if (!is_dir(public_path($savePath))) {
                mkdir(public_path($savePath));
            }

            $pathInfo = pathinfo($path);

            if (empty($filename)) {
                $filename = 'doc-truyen-tranh-moi-' . md5(time()) . basename($path);

                $filename = str_slug($filename);
            } else {
                $filename = $filename . '-' . md5(time());
            }

            $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : 'jpg';

            $filename .= '.' . $extension;

            $filename = '/' . $folder . '/' . $filename;

            $image->save(public_path($filename));

            if ($old) {
                @unlink(public_path($old));
            }
        } catch (\Exception $ex) {
//            dd($ex->getMessage());
            $filename = '/themes/home/images/404-avatar.png';
        }

        return $filename;
    }

    public static function detectPhoneNumber($string) {
        preg_match('/\+?[0-9][0-9()\-.\s+]{7,20}[0-9]/', $string, $matches);
        $phone = preg_replace('/[^0-9]/', '', $matches);

        if (count($phone) > 0) {
            $phone = $phone[0];

            if ($phone[0] == 8 and $phone[1] == 4) {
                $phone = preg_replace('/84/', '0', $phone, 1);
            } elseif ($phone[0] != 0) {
                $phone = '0' . $phone;
            }

            if (strlen($phone) >= 10 && strlen($phone) <= 11) {
                return $phone;
            }
        }

        return '';
    }
}