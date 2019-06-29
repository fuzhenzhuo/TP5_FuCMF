<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/2/8
 * Time: 13:30
 */

namespace app\common\library;

use Qiniu\Auth;


class Upload
{
    /**
     * @param string $type
     * @return array|mixed|string
     * 未经过压缩上传 可以长传文件（Excle）
     */
    public static function upload($type = 'image')
    {
        $i = 0;

        foreach ($_FILES as $file) {

            if (is_string($file['name'])) {
                $time_path = '.' . "/uploads/$type/" . date('Ymd');

                if (!file_exists($time_path)) {
                    mkdir($time_path, 0777, true);
                }
                $ext = substr($file['name'], strpos($file['name'], '.'));

                $filename = $time_path . '/' . uniqid() . $ext;

                $ree = move_uploaded_file($file['tmp_name'], $filename);

                if ($ree != 'true') {

                    return Y::json([
                        'msg' => '移动失败'
                    ]);
                };
                return $filename;


            } elseif (is_array($file['name'])) {

                foreach ($file['tmp_name'] as $key => $val) {

                    $time_path = '.' ."/uploads/$type/" . date('Ymd');

                    if (!file_exists($time_path)) {
                        mkdir($time_path, 0777, true);
                    }
                    $ext = substr($file['name'][$i], strpos($file['name'][$i], '.'));
                    $filename = $time_path . '/' . uniqid() . $ext;

                    $res = move_uploaded_file($file['tmp_name'][$i], $filename);

                    if ($res != 'true') {
                        return Y::json([
                            'msg' => '移动失败'
                        ]);
                    };
                    $data[] = $filename;

                    $i++;
                }
                return $data;
            }

        }
    }

    /**
     * @param string $type
     * @return array|mixed|string
     * 压缩图片后上传
     */
    public static function upload1($type = 'image')
    {
        $i = 0;

        foreach ($_FILES as $file) {

            if (is_string($file['name'])) {
                $time_path = '.' . "/uploads/$type/" . date('Ymd');

                if (!file_exists($time_path)) {
                    mkdir($time_path, 0777, true);
                }
                $ext = substr($file['name'], strpos($file['name'], '.'));

                $filename = $time_path . '/' . uniqid() . $ext;

                $ree = move_uploaded_file($file['tmp_name'], $filename);

                if ($ree != 'true') {

                    return Y::json([
                        'msg' => '移动失败'
                    ]);
                };

                $filename = self::compressedImage($filename,$filename);
                return $filename;

            } elseif (is_array($file['name'])) {

                foreach ($file['tmp_name'] as $key => $val) {

                    $time_path = '.' ."/uploads/$type/" . date('Ymd');

                    if (!file_exists($time_path)) {
                        mkdir($time_path, 0777, true);
                    }
                    $ext = substr($file['name'][$i], strpos($file['name'][$i], '.'));
                    $filename = $time_path . '/' . uniqid() . $ext;

                    $res = move_uploaded_file($file['tmp_name'][$i], $filename);

                    if ($res != 'true') {
                        return Y::json([
                            'msg' => '移动失败'
                        ]);
                    };
//                    $data[] = $filename;
                    $data[] = self::compressedImage($filename,$filename);

                    $i++;
                }
                return $data;
            }

        }
    }

    /**
     * desription 判断是否gif动画
     * @param sting $image_file图片路径
     * @return boolean t 是 f 否
     */
    public static function check_gifcartoon($image_file){
        $fp = fopen($image_file,'rb');
        $image_head = fread($fp,1024);
        fclose($fp);
        return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?false:true;
    }


    /**
     * desription 压缩图片
     * @param sting $imgsrc 图片路径
     * @param string $imgdst 压缩后保存路径
     */
    public static function compressedImage($imgsrc, $imgdst) {

        list($width, $height, $type) = getimagesize($imgsrc);

        $new_width = $width;//压缩后的图片宽
        $new_height = $height;//压缩后的图片高

        if($width >= 500){
            $per = 500 / $width;//计算比例
            $new_width = $width * $per;
            $new_height = $height * $per;
        }

        switch ($type) {
            case 1:
                $giftype =static::check_gifcartoon($imgsrc);
                if ($giftype) {
                    header('Content-Type:image/gif');
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    //90代表的是质量、压缩图片容量大小
                    imagejpeg($image_wp, $imgdst, 70);
                    imagedestroy($image_wp);
                    imagedestroy($image);
                }
                return $imgdst;
                break;
            case 2:
                header('Content-Type:image/jpeg');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 70);
                imagedestroy($image_wp);
                imagedestroy($image);
                return $imgdst;
                break;
            case 3:
                header('Content-Type:image/png');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 70);
                imagedestroy($image_wp);
                imagedestroy($image);
                return $imgdst;
                break;
        }
    }



}
