<?php

namespace app\v1\controller;

use app\common\library\Y;
use think\Controller;
use think\Db;

class MainController extends Controller
{
    /**
     * 更新代码时 启用维护模式
     *
     * @return \think\Response
     */
    public function index()
    {

        // 1维护 0正常运行
        $status = Db::table('main')->where('id',1)->find();


       return Y::json(0,'维护模式',$status);

    }


}
