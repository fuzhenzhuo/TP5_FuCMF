<?php

namespace app\v1\controller;

use app\common\library\ArrayHelp;
use lea21st\Auth;
use lea21st\jwt\JWT;
use lea21st\Log;
use think\Db;
use app\common\library\Y;
use think\facade\Cache;
use think\Validate;
use app\common\library\Hash;

class PublicController
{
    /**
     * 登录
     */
    public function login()
    {
        $phone = input('phone', '');
        $password = input('password', '');

        $data = [
            'phone' => $phone
        ];

        $validate = new Validate([
            'phone' => 'require|max:11|/^1[3-9]{1}[0-9]{9}$/',
        ]);

        if (!$validate->check($data)) {
            return Y::json('0', $validate->getError());
        }
        $pwd = Db::table('user')->where('mobile', $phone)->value('password');

        $password = md5($password);

        if ($password != $pwd) {
            return Y::json(1, '对不起您输入的密码不正确');
        }


        $info = Db::table('user')->where('mobile', $phone)->find();

        if ($info['status'] == 0) {

            return Y::json(1, '对不起此账号已被禁用');
        }

        unset($info['password']);

        $auth = \app\common\library\Auth::instance();
        $token = $auth->token($info['id']);
        $info['token'] = $token;
        if ($info) {
            return Y::json(0, '登录成功', $info);
        }
    }


    /**
     * 注册
     */
    public function rigister()
    {
        $data = input();

        if (empty($data['id'])) {

            return Y::json(1, '对不起,您必须扫描推荐人二维码');
        }

        $validate = new Validate([
            'phone' => 'require|max:11|/^1[3-9]{1}[0-9]{9}$/',
        ]);
        if (!$validate->check($data)) {
            return Y::json('0', $validate->getError());
        }
        $info = Db::table('user')->where('mobile', $data['phone'])->find();
        if ($info) {
            return Y::json(1, '对不起手机号已注册');
        }

        $cod = Cache::get($data['phone']);
        if ($cod != $data['code']) {
            return Y::json(1, '验证码或手机错误');
        }
        //加密
        $password = md5($data['password']);


        $register_time = date('Y-m-d H:i:s');

        $new_id = Db::table('user')->insertGetId(['password' => $password, 'mobile' => $data['phone'], 'nickname' => $data['name'], 'hhr_jb' => 'A', 'register_time' => $register_time]);



        if ($new_id) {
            return Y::json(0, '注册成功');
        }
    }


    //找回密码
    public function backPass()
    {
        $phone = input('phone','');
        $code  =input('code','');
        $password = input('password','');

        $mobile = Db::table('user')->where('mobile',$phone)->value('mobile');

        if ($phone == $mobile){
            $cod = Cache::get($phone);
            if ($cod != $code) {
                return Y::json(1, '验证码或手机号错误');
            }else{

                $res = Db::table('user')->where('mobile',$phone)->setField('password',md5($password));
                if ($res){
                    return Y::json(0,'修改密码成功');
                }
            }

        }else{
            return Y::json(1, '请输入原手机号');

        }

    }

    /**
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 超过24H未支付 清除订单
     */
    public function delOrder()
    {
        $data = Db::table('order')->select();

        foreach ($data as $v) {
            $t = time();
            $s = 60 * 60 * 24;
            $t3 = $t - $s;

            if ($v['status'] == 0) {

                $t2 = strtotime($v['order_time']);
                if ($t3 >= $t2) {
                    $result = Db::table('order')->where('id',$v['id'])->delete();
                }

            }
        }

        return Y::json(0,'清除未支付订单成功');

    }


}
