<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/2/8
 * Time: 13:30
 */

namespace app\common\library;

use Qiniu\Auth;

class Qiniu
{

    public static function getIns()
    {
        $config = config('upload');
        static $auth;
        if (!isset($auth)) {
            $auth = new Auth($config['AccessKey'], $config['SecretKey']);
        }
        return $auth;
    }

    /**
     * 获取上传token
     * @param string $bucket
     * @param null $policy
     * @return string
     */
    public static function getToken($bucket = 'file', $policy = null)
    {
        if (!$policy) {
            $token = self::getIns()->uploadToken($bucket);
        } else {
            $token = self::getIns()->uploadToken($bucket, null, 3600, $policy, true);
        }
        return $token;
    }

    //获取上传url
    public static function getUploadUrl($bucket)
    {
        $callback   = url('public/callback', '', '', true);
        $token      = self::getToken($bucket, [
            'returnUrl' => $callback,
        ]);
        $upload_url = config('upload.UploadUrl');
        return $upload_url . '?token=' . $token;
    }

    /**
     * 获取文件信息
     * @param $key
     * @return mixed
     */
    public function getFileInfo($bucket, $key)
    {
        $bucketManager = new \Qiniu\Storage\BucketManager(self::getIns(), (new Config()));
        list($fileInfo, $err) = $bucketManager->stat($bucket, $key);
        if ($err) {
            return $err;
        } else {
            return $fileInfo;
        }
    }


    /**
     * 获取下载链接
     * @param $key
     * @param $bucket
     * @param string $style
     * @return string
     */
    public static function getDownloadUrl($key, $bucket, $style = '')
    {
        $config  = config('upload');
        $config  = $config['bucket'][$bucket];
        $baseUrl = $config['host'] . '/' . $key;
        if ($style) {
            $baseUrl .= '?' . $style;
        }
        if ($config['private'] === true) {
            // 对链接进行签名
            $baseUrl = self::getIns()->privateDownloadUrl($baseUrl, 12 * 3600);
        }
        return $baseUrl;
    }
}
