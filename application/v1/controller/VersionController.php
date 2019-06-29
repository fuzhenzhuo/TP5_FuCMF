<?php

namespace app\v1\controller;

use app\common\library\Y;
use think\Controller;
use think\Db;
use think\facade\Cache;
use think\Request;

class VersionController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $version = input('version','');

        $new = Db::table('version')->where('id',1)->value('new');
        $link = 'http://39.100.69.132/packet/lishuaParner.apk';
        $link2 = 'http://39.100.69.132/packet/lishuaParner.ipa';

        if ($version == $new){

            return Y::json(1,'您使用的是最新版本');

        }else{

            return Y::json(0,'有新版本，请您更新获得更好体验',['link'=>$link,'link2'=>$link2]);

        }


    }


}
