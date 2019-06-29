<?php

namespace app\v1\controller;

use app\common\library\Y;
use app\v1\model\Order;
use Payment\Client\Charge;
use Payment\Config;
use think\Controller;
use think\Db;
use think\Request;

class RepayController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $user = app()->user;

        $data = Db::table('repay')->where('user_id', $user['id'])->select();

        $result = $data;

        foreach ($data as $v) {

            if ($v['status'] == 1) {

                $data = [];

            } else {

            $data = $result;

            }
        }

        return Y::json(0, '授信还款列表', $data);

    }

    public function repayStatus()
    {
        $id = input('id', '');

        $status = input('status', '');

        $order_no = input('order_no', '');


        if ($status == 1) {


            $data = Db::table('repay')->where('id', $id)->update(['status' => $status, 'order_no' => $order_no,'payType'=>2]);

            if ($data) {
                return Y::json(0, '还款成功');
            }

        } else {

            return Y::json(1, '还款失败');

        }


    }


}
