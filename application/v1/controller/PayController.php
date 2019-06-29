<?php

namespace app\v1\controller;

use app\common\library\Y;
use app\v1\model\Order;
use Payment\Client\Charge;
use Payment\Config;
use think\Controller;
use think\Db;
use think\Request;

class PayController extends Controller
{

    /**
     * 显示资源列表
     * 下订单 与支付接口
     * @return \think\Response
     */
    public function PayApp(Request $request)
    {
        $pay_way = input('pay_way', '');
        $money = intval(input('money', ''));
        $order = input('order_no', '');
        $subject = input('title','');

        if (!in_array($pay_way, ['ali_app', 'wx_app'])) {
            return Y::json(1001, '不支持当前支付方式');
        }


        //生成支付订单
        $payData = [
            'body' => 'goods',
            'subject' => $subject,
            'order_no' =>$order,
            'amount' => $money,
            'timeout_express' => time() + 15 * 60,
            'client_ip' => $request->ip(),
            'goods_type' => '1',
            'return_param' => 'recharge'
        ];


        //判断支付 ali_app OR wx_app

        $type = $pay_way == Config::ALI_CHANNEL_APP ? Config::ALI_CHARGE : Config::WX_CHARGE;
        $config = config('payment.');

        //支付
        try {
            $payment = Charge::run($pay_way, $config[$type], $payData);

            return Y::json([
                'order_no' => $payData['order_no'],
                 Config::ALI_CHANNEL_APP => $pay_way == Config::ALI_CHANNEL_APP && !empty($payment) ? $payment : '',
                 Config::WX_CHANNEL_APP => $pay_way == Config::WX_CHANNEL_APP && !empty($payment) ? $payment : '',
//                 Config::WX_CHANNEL_APP => $pay_way == Config::WX_CHANNEL_APP && !empty($payment) ? $payment : (new \stdClass()),
            ]);
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }

    //更新订单支付状态
    public function payStatus()
    {
        $status = input('status','');
        $order_no = input('order_no','');
        $order_status = Db::table('order')->where('order',$order_no)->value('order_status');

        $user =app()->user;

        if ($status ==1){

            $num = Db::table('order')->where('order',$order_no)->value('num');

            if ($num >= 10) {
                $jb = Db::table('user')->where('id', $user['id'])->value('hhr_jb');

                if ($jb == 'K') {
                    Db::table('user')->where('id',$user['id'])->setField('hhr_jb', 'A');
                }

            }


            if ($order_status ==0){
                $m = Db::table('cashback')->where('type', 'invite')->value('money');

                $pid = Db::table('user')->where('id',$user['id'])->value('parend_id');

                $p_money = Db::table('purse')->where('user_id',$pid)->order('id','desc')->value('total_money');

                $p_total_money =bcadd($p_money,$m,2);

                Db::table('purse')->insert(['user_id'=>$pid,'invite'=>$m,'invite_user'=>$user['id'],'total_money'=>$p_total_money,'trade_time'=>date('Y-m-d H:i:s'),'create_time'=>date('Y-m-d H:i:s')]);
            }
            $result = Db::table('order')->where('order',$order_no)->update(['status'=>$status,'payType'=>2]);
            if ($result){
                return Y::json(0,'订单支付状态修改成功');
            }
        }else{
            return Y::json(1,'支付失败');
        }

    }





}
