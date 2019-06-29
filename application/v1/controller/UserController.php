<?php

namespace app\v1\controller;

use app\common\library\Auth;
use app\common\library\Upload;
use app\common\library\Y;
use think\App;
use think\Controller;
use think\Db;
use think\Validate;
use app\v1\model\Order;
use think\facade\Cache;

class UserController extends Controller
{
    /**
     * 实名认证
     */
    public function realName()
    {
        if (request()->isGet()) {
            //身份证号
            $id_card = input('card', '');
            $name = input('name', '');

            $data = [
                'card' => $id_card,
                'name' => $name
            ];

            $validate = new Validate([
                'card' => 'require|max:18',
                'name' => 'require',
            ]);

            if (!$validate->check($data)) {
                return Y::json('0', $validate->getError());
            }
            $user = app()->user;

            $result = Db::table('user')->where('id', $user['id'])->update(['id_card' => $id_card, 'nickname' => $name]);

            if ($result == 1) {
                return Y::json(0, '填写成功');
            } elseif (($result == 0)) {
                return Y::json(1, '请不要填写重复之前的信息');
            }

        } elseif (request()->isPost()) {

            $file = $_FILES;
            $user = app()->user;

            if (!empty($file['cover'])) {
                $data['cover'] = $file['cover'];
            }


            $res = Db::table('cert_pig')->where('pid', $user['id'])->find();


            if (!empty($res['cove1'])) {
                $cover1 = '.' . $res['cove1'];
                unlink($cover1);
                if (!empty($res['cove2'])) {
                    $cover2 = '.' . $res['cove2'];
                    unlink($cover2);
                }
                Db::table('cert_pig')->where('pid', $user['id'])->delete();

            }


            if (!empty($data['cover'])) {
                $img_path = Upload::upload1('image', $data['cover']);
                if (!is_string($img_path)) {
                    foreach ($img_path as $k => $v) {
                        $img_path = substr($v, strpos($v, '/upload'));
                        if ($k == 0) {
                            $result = db::table('cert_pig')->insertGetId(['pid' => $user['id'], 'cove1' => $img_path]);
                        } elseif ($k == 1) {
                            $result = db::table('cert_pig')->where('pid', $user['id'])->update(['cove2' => $img_path]);
                        }
                    }

                    if (!$result) {
                        return Y::json('1', '上传失败');
                    }

                } else {
                    $img_path = substr($img_path, strpos($img_path, '/upload'));
                    $result = db::table('cert_pig')->insert(['pid' => $user['id'], 'cove1' => $img_path]);
                    if (!$result) {
                        return Y::json('1', '上传失败');
                    }
                }
            }

            $do = Db::table('user')->where('id', $user['id'])->setField('real', 0);

            if ($result) {
                return Y::json(0, '上传成功请耐心等待审核');
            }

        }

    }

    /**
     * @return mixed
     * 下订单列表
     */
    public function order()
    {

        $user = app()->user;

        if (request()->isGet()) {
            // 查询有没有下过订单
            $order_status = Db::table('order')->where('user_id', $user['id'])->where('status', 1)->count();

            if ($order_status >= 1) {

                $data = Db::table('cashback')
                    ->field('num,money,pos_money')
                    ->where('type', 'order2')
                    ->select();

                $tag = 1;

                return Y::json(0, '补货订单信息', ['data' => $data, 'tag' => $tag]);
            } else {

                $data = Db::table('cashback')
                    ->field('num,money,pos_money')
                    ->where('type', 'order1')
                    ->select();

                $tag = 0;
                return Y::json(0, '首次订单信息', ['data' => $data, 'tag' => $tag]);
            }

        } else {


            $num = input('num', '');
            $address = input('address', '');
            $money = input('money', '');

            $order_status = db('order')->where('user_id', $user['id'])->where('status', 1)->count();

            if ($order_status > 0) {
                $order = Order::build_order_no('R');
                $order_time = date('Y-m-d H:i:s');
                $result = Db::table('order')->insert(['user_id' => $user['id'], 'num' => $num, 'address' => $address, 'money' => $money, 'order' => $order, 'order_time' => $order_time, 'order_status' => 1]);
            } else {
                $res = db('order')->where('user_id', $user['id'])->value('status');
                if (isset($res)) {
                    if ($res == 0) {
                        return Y::json(0, '您有首次订单未支付，请到订单列表中支付');
                    }
                }

                $order = Order::build_order_no('R');
                $order_time = date('Y-m-d H:i:s');
                $result = Db::table('order')->insert(['user_id' => $user['id'], 'num' => $num, 'address' => $address, 'money' => $money, 'order' => $order, 'order_time' => $order_time, 'order_status' => 0]);
            }

        }


        if ($result) {
            return Y::json(1, '下单成功，请您尽快支付', ['order_no' => $order]);
        }
    }

    //订单状态 已支付 未支付
    public function order_status()
    {   //0未支付 ，1已支付
        $tag = input('tag', '');
        $p = input('page', '20');

        $user = app()->user;

        $data = Db::table('order')
            ->when($tag == '0', function ($q) use ($tag) {
                return $q->where('status', $tag);
            })->when($tag == '1', function ($q) use ($tag) {
                return $q->where('status', $tag);
            })->where('user_id', $user['id'])
            ->order('id', 'desc')
            ->select();


        return Y::json(0, '订单信息', $data);

    }


    /**
     * 修改登录密码
     */
    public function fixPass()
    {
        $phone = input('phone', '');
        $pwd = input('pwd', '');
        $new_pwd = input('new_pwd', '');
        $user = app()->user;

        $user_info = Db::table('user')->where('id', $user['id'])->where('mobile', $phone)->where('password', md5($pwd))->find();

        if ($user_info == NULL) {
            return Y::json(1, '对不起您输入错误');
        } else {

            $info = Db::table('user')->where('id', $user['id'])->where('mobile', $phone)->update(['password' => md5($new_pwd)]);

            if ($info == 1) {
                return Y::json(0, '修改成功');
            } elseif ($info == 0) {
                return Y::json(1, '请不要修改与原来一样的密码');
            }
        }

    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 通讯录
     */
    public function book()
    {
        $user = app()->user;

        if (!empty($user['tuij'])) {
            $user = explode(',', $user['tuij']);
            foreach ($user as $v) {
                $user_info[] = Db::table('user')->field('id,nickname,mobile,address,hhr_jb')->where('id', $v)->find();
            }

            return Y::json(0, '您的好友通讯录为', $user_info);

        } else {
            return Y::json(0, '您还没有好友，请先寻找合伙人');
        }

    }

    /**
     * 排行榜
     */
    public function phb()
    {
        $tag = input('tag', 'td_jy');

        $user = Db::table('team_jy')
            ->when($tag, function ($q) use ($tag) {
                return $q->order($tag, 'desc');
            })->select();

        return Y::json(0, '排行榜', $user);

    }

    /**
     * 直营业绩
     */
    public function zy_yj()
    {
        // all 返回所有  m 返回当月
        $tag = input('tag', 'all');
        $user = app()->user;
        $data = Db::table('team_jy')->where('user_id', $user['id'])
            ->when($tag == 'all', function ($q) {
                return $q->order('user_id', 'desc');
            })->when($tag == 'm', function ($q) use ($user) {
                $t = $user['id'] . '.' . date('Y-m');
                return $q->where('utime', $t);
            })->field('zy_jy')->select();

        if ($tag == 'all') {
            $n = 0;
            foreach ($data as $v) {
                $n += $v['zy_jy'];
            }
            return Y::json(0, '直营总业绩', ['money' => $n]);
        } else {
            $n = 0;
            foreach ($data as $v) {
                $n = $v['zy_jy'];
            }
            return Y::json(0, '直营当月业绩', ['money' => $n]);
        }

    }

    /**
     * 直营新增合伙人
     * 每个月的数据
     */
    public function new_hhr()
    {

        $user = app()->user;

        $tuij = explode(',', $user['tuij']);

        $n = [];
        foreach ($tuij as $v) {

            $u = Db::table('user')->where('id', $v)->whereTime('register_time', 'month')->find();

            if ($u != NULL) {
                array_push($n, $u);
            }

        }
        $num = count($n);

        return Y::json(0, '新增合伙人', ['num' => $num]);
    }

    //直营昨日激活机器
    public function jhmach()
    {
        $user = app()->user;
        $n = [];
        $n = Db::table('mach')->where('phone', $user['mobile'])->whereTime('activite_time', 'yesterday')->where('activate', 1)->select();
        $result = count($n);

        return Y::json(0, '昨日激活机器', ['num' => $result]);

    }


    //团队 总接口
    public function td()
    {
        //团队当月总收益
        $user = app()->user;
        $time = $user['id'] . '.' . date('Y-m');
        $data = Db::table('team_jy')->where('user_id', $user['id'])->where('utime', $time)->field('td_jy')->select();
        $money = 0;
        foreach ($data as $v) {
            $money += $v['td_jy'];
        }


//      -----------------------------------------------

        //团队昨日激活

        $user_info = explode(',', $user['xjhhr']);

        $data = [];
        foreach ($user_info as $v) {
            if ($user['id'] != $v) {
                $p = Db::table('user')->where('id', $v)->value('mobile');

                $u = Db::table('mach')->where('phone', $p)->whereTime('activite_time', 'yesterday')->where('activate', 1)->select();

                foreach ($u as $vb) {
                    array_push($data, $vb);
                }
            }
        }
        $jh = count($data);

//      -----------------------------------------------
        //团队人数

        $user_info = explode(',', $user['xjhhr']);

        $td_jh = count($user_info);

        //减去自己人头
        $td_jh = $td_jh - 1;

        return Y::json(0, '团队数据', [
            'money' => $money,
            'zrjh' => $jh,
            'td_jh' => $td_jh,
        ]);

    }


    /**
     * 推荐奖励
     */
    public function tuij()
    {

        $user = app()->user;

        $result = Db::table('purse')->alias('a')
            ->leftJoin('user b', 'a.invite_user = b.id')
            ->field('a.*,b.nickname,b.mobile')
            ->order('create_time', 'desc')
            ->where('user_id', $user['id'])
            ->select();

        $data = [];
        foreach ($result as $v) {
            if (!empty($v['invite_user'])) {
                array_push($data, $v);
            }
        }

        return Y::json(0, '推荐奖励明细', $data);

    }

    /**
     * 激活奖励
     */
    public function jh_mach()
    {


        $user = app()->user;

        $result = Db::table('purse')->where('user_id', $user['id'])->field('pos_jh,pos_id,trade_time')->order('trade_time', 'desc')->select();

        $data = [];
        foreach ($result as $v) {
            if (!empty($v['pos_id'])) {
                array_push($data, $v);
            }
        }

        return Y::json(0, '激活奖励明细', $data);

    }


    /**
     * 申请提现
     */
    public function tx()
    {


        if ($this->request->isPost()) {

            $money = input('money', '');

            $user = app()->user;


            $real = Db::table('user')->where('id', $user['id'])->value('real');


            if ($real == 0) {

                return Y::json(1, '实名认证后才能提现');

            }

            if ($real == 2) {
                return Y::json(3, '您已上传认证信息，请耐心等待');
            }


            $n = Db::table('order')->where('user_id', $user['id'])->where('status', 1)->count();

            if ($n <= 0) {
                return Y::json(1, '必须购买过机具才能提现');
            }


            $status = Db::table('repay')->where('user_id', $user['id'])->whereTime('tm', 'month')->value('status');

            if (isset($status)) {
                if ($status == 0) {
                    return Y::json(1, '未还款不能提现');
                }
            }


            $info = Cache::get($user['id']);

            if (!empty($info)) {
                return Y::json(1, '每天只能提现一次');
            }

            $t1 = strtotime(date('Y-m-d H:i:s'));
            $t2 = strtotime(date("Y-m-d 00:00:00", strtotime("+1 day")));

            $time = $t2 - $t1;

            Cache::set($user['id'], 'tx', $time);


            $data = Db::table('tx_set')->find();

            if ($money < $data['min_tx']) {
                return Y::json(1, "提现金额至少" . $data['min_tx']);
            }


            $ye = Db::table('purse')->where('user_id', $user['id'])->order('id', 'desc')->value('total_money');
            //申请提现金额 大于余额 return
            if ($money > $ye) {

                return Y::json(1, "可提余额不足");
            }

            // 税款
            $tax_money = $money * $data['lv'];

            //申请提现金额 -税款 = 应提金额
            $tx_money = bcsub($money, $tax_money, 2);

            //提现金额小于500扣2块手续费

            if ($money < 500) {
                $tx_money = bcsub($tx_money, 2, 2);
            }

            //申请提现后 剩余的 余额
            $total_ye = bcsub($ye, $money, 2);


            Db::table('purse')->insert(['user_id' => $user['id'], 'total_money' => $total_ye, 'tx' => $money, 'trade_time' => date('Y-m-d H:i:s'), 'create_time' => date('Y-m-d H:i:s')]);

            //提现金额小于500扣2块手续费

            if ($money < 500) {
                $result = Db::table('tx')->insert(['user_id' => $user['id'], 'money' => $money, 'tax_money' => $tax_money, 'tx_money' => $tx_money, 'charge' => 2, 'create_time' => date('Y-m-d H:i:s')]);

            } else {
                $result = Db::table('tx')->insert(['user_id' => $user['id'], 'money' => $money, 'tax_money' => $tax_money, 'tx_money' => $tx_money, 'create_time' => date('Y-m-d H:i:s')]);
            }


            if ($result) {
                return Y::json(0, '申请成功，等待审核');
            }

        } else {

            $user = app()->user;

            $total_money = Db::table('purse')->where('user_id', $user['id'])->order('id', 'desc')->value('total_money');


            if ($total_money == '') {
                $total_money = 0;
            }

            return Y::json(0, '可用余额', ['money' => $total_money]);

        }

    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 历史业绩
     */
    public function history()
    {

        $time = input('tm', '');

        $uid = input('id', '');

        $t = strlen($time);


        //查他的所有直接下级
        $user_info = Db::table('user')->where('id', $uid)->value('tuij');

        $ids = explode(',', $user_info);

        $mach_num = 0;

        $user_arr = [];

        $user = [];


        if ($t == 7) {

            $tm = $time .'-01';

            $tm1 = date("$time-t", strtotime('-1 month'));


            foreach ($ids as $id) {

                if ($id != $uid) {

                    $result = Db::table('user')->whereTime('register_time','between', [$tm,$tm1])->field('id')->select();

                    foreach ($result as $value) {
                        foreach ($value as $v) {

                            array_push($user_arr, $v);

                        }
                    }

                    if (in_array($id, $user_arr)) {

                        array_push($user, $id);

                    }

                    $mach_num += Db::table('mach')->where('user_id', $id)->where('activate', 1)->whereTime('activite_time', 'between', [$tm,$tm1])->count();

                }

            }

            //团队新增合伙人数量
            $user_num = count($user);

            //团队交易额
            $tm = $uid . '.' . $time;

            $total_trade = Db::table('team_jy')->where('user_id', $uid)->where('utime', $tm)->value('zy_jy');

            if ($total_trade == null){
                $total_trade =0 ;
            }

        } else {
            foreach ($ids as $id) {

                if ($id != $uid) {

                    $result = Db::table('user')->where('register_time', $time)->field('id')->select();

                    foreach ($result as $value) {
                        foreach ($value as $v) {
                            array_push($user_arr, $v);
                        }
                    }

                    if (in_array($id, $user_arr)) {

                        array_push($user, $id);
                    }
                    $mach_num += Db::table('mach')->where('user_id', $id)->where('activate', 1)->where('activite_time', $time)->count();
                }
            }

            //团队新增合伙人数量
            $user_num = count($user);

            $total_trade = Db::table('trade')->where('user_id', $uid)->where('trade_time', $time)->sum('meny');

        }

        return Y::json(1, '团队历史业绩', ['user_num' => $user_num, 'total_trade' => $total_trade, 'mach_num' => $mach_num]);

    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 收益统计钱包(每月)
     */
    public function revenue()
    {
        $user = app()->user;


        $t1 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-1 month')), date('Y-m-t', strtotime('-1 month'))])->select();


        $n1 = 0;

        foreach ($t1 as $v1) {
            $n1 = bcadd($n1, $v1['invite'], 2);
            $n1 = bcadd($n1, $v1['pos_jh'], 2);
            $n1 = bcadd($n1, $v1['zy_share'], 2);
            $n1 = bcadd($n1, $v1['team_share'], 2);
        }
        $tm = date('Y-m', strtotime('-1 month'));
        $m1 = [$tm, $n1];


        $t2 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-2 month')), date('Y-m-t', strtotime('-2 month'))])->select();


        $n2 = 0;

        foreach ($t2 as $v2) {
            $n2 = bcadd($n2, $v2['invite'], 2);
            $n2 = bcadd($n2, $v2['pos_jh'], 2);
            $n2 = bcadd($n2, $v2['zy_share'], 2);
            $n2 = bcadd($n2, $v2['team_share'], 2);
        }
        $tm = date('Y-m', strtotime('-2 month'));
        $m2 = [$tm, $n2];


        $t3 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-3 month')), date('Y-m-t', strtotime('-3 month'))])->select();

        $n3 = 0;
        foreach ($t3 as $v3) {
            $n3 = bcadd($n3, $v3['invite'], 2);
            $n3 = bcadd($n3, $v3['pos_jh'], 2);
            $n3 = bcadd($n3, $v3['zy_share'], 2);
            $n3 = bcadd($n3, $v3['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-3 month'));
        $m3 = [$tm, $n3];


        $t4 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-4 month')), date('Y-m-t', strtotime('-4 month'))])->select();

        $n4 = 0;

        foreach ($t4 as $v4) {
            $n4 = bcadd($n4, $v4['invite'], 2);
            $n4 = bcadd($n4, $v4['pos_jh'], 2);
            $n4 = bcadd($n4, $v4['zy_share'], 2);
            $n4 = bcadd($n4, $v4['team_share'], 2);
        }
        $tm = date('Y-m', strtotime('-4month'));
        $m4 = [$tm, $n4];


        $t5 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-5 month')), date('Y-m-t', strtotime('-5 month'))])->select();

        $n5 = 0;

        foreach ($t5 as $v5) {
            $n5 = bcadd($n5, $v5['invite'], 2);
            $n5 = bcadd($n5, $v5['pos_jh'], 2);
            $n5 = bcadd($n5, $v5['zy_share'], 2);
            $n5 = bcadd($n5, $v5['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-5 month'));
        $m5 = [$tm, $n5];


        $t6 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-6 month')), date('Y-m-t', strtotime('-6 month'))])->select();

        $n6 = 0;

        foreach ($t6 as $v6) {
            $n6 = bcadd($n6, $v6['invite'], 2);
            $n6 = bcadd($n6, $v6['pos_jh'], 2);
            $n6 = bcadd($n6, $v6['zy_share'], 2);
            $n6 = bcadd($n6, $v6['team_share'], 2);
        }
        $tm = date('Y-m', strtotime('-6 month'));
        $m6 = [$tm, $n6];


        $t7 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-7 month')), date('Y-m-t', strtotime('-7 month'))])->select();

        $n7 = 0;

        foreach ($t7 as $v7) {
            $n7 = bcadd($n7, $v7['invite'], 2);
            $n7 = bcadd($n7, $v7['pos_jh'], 2);
            $n7 = bcadd($n7, $v7['zy_share'], 2);
            $n7 = bcadd($n7, $v7['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-7 month'));
        $m7 = [$tm, $n7];


        $t8 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-8 month')), date('Y-m-t', strtotime('-8 month'))])->select();

        $n8 = 0;

        foreach ($t8 as $v8) {
            $n8 = bcadd($n8, $v8['invite'], 2);
            $n8 = bcadd($n8, $v8['pos_jh'], 2);
            $n8 = bcadd($n8, $v8['zy_share'], 2);
            $n8 = bcadd($n8, $v8['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-8 month'));
        $m8 = [$tm, $n8];


        $t9 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-9 month')), date('Y-m-t', strtotime('-9 month'))])->select();

        $n9 = 0;

        foreach ($t9 as $v9) {
            $n9 = bcadd($n9, $v9['invite'], 2);
            $n9 = bcadd($n9, $v9['pos_jh'], 2);
            $n9 = bcadd($n9, $v9['zy_share'], 2);
            $n9 = bcadd($n9, $v9['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-9 month'));
        $m9 = [$tm, $n9];


        $t10 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-10 month')), date('Y-m-t', strtotime('-10 month'))])->select();

        $n10 = 0;
        foreach ($t10 as $v10) {
            $n10 = bcadd($n10, $v10['invite'], 2);
            $n10 = bcadd($n10, $v10['pos_jh'], 2);
            $n10 = bcadd($n10, $v10['zy_share'], 2);
            $n10 = bcadd($n10, $v10['team_share'], 2);
        }
        $tm = date('Y-m', strtotime('-10 month'));
        $m10 = [$tm, $n10];


        $t11 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-11 month')), date('Y-m-t', strtotime('-11 month'))])->select();

        $n11 = 0;

        foreach ($t11 as $v11) {
            $n11 = bcadd($n11, $v11['invite'], 2);
            $n11 = bcadd($n11, $v11['pos_jh'], 2);
            $n11 = bcadd($n11, $v11['zy_share'], 2);
            $n11 = bcadd($n11, $v11['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-11 month'));
        $m11 = [$tm, $n11];


        $t12 = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'between', [date('Y-m-01', strtotime('-12 month')), date('Y-m-t', strtotime('-12 month'))])->select();

        $n12 = 0;

        foreach ($t12 as $v12) {
            $n12 = bcadd($n12, $v12['invite'], 2);
            $n12 = bcadd($n12, $v12['pos_jh'], 2);
            $n12 = bcadd($n12, $v12['zy_share'], 2);
            $n12 = bcadd($n12, $v12['team_share'], 2);
        }

        $tm = date('Y-m', strtotime('-12 month'));
        $m12 = [$tm, $n12];


        $year = [$m1, $m2, $m3, $m4, $m5, $m6, $m7, $m8, $m9,
            $m10, $m11, $m12];


        return Y::json(0, '每月收益统计', $year);

    }

    /**
     * @return mixed
     * 绑定手机号
     */
    public function bindPhone()
    {
        $phone = input('phone', '');
        $code = input('code', '');
        $cod = Cache::get($phone);

        if ($cod != $code) {
            return Y::json(1, '验证码或手机错误');
        }

        $user = app()->user;
        $data = db('user')->where('id', $user['id'])->setField('mobile', $phone);
        Db::table('mach')->where('user_id', $user['id'])->setField('phone', $phone);

        if ($data == 1) {
            return Y::json(0, '绑定成功');
        } else {
            return Y::json(0, '您绑定的是以前的号码');
        }
    }

    /**
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 绑定银行卡
     */
    public function bindBank_Card()
    {
        $card = input('card', '');
        $card_name = input('card_name', '');
        $user = app()->user;
        $result = Db::table('user')->where('id', $user['id'])->update(['bank_card' => $card, 'bank_name' => $card_name]);

        if ($result) {
            return Y::json(0, '绑定成功');
        }

    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 关于我们
     */
    public function about()
    {
        $data = Db::table('about')->select();

        return Y::json(0, '关于我们', $data);

    }

    /**
     * 划拨
     */
    public function hb()
    {
        $user = app()->user;

        if (request()->isGet()) {

            //agent 代理商   client 客户
            $tag = input('tag', '');
            if ($tag == 'agent') {

                $xj = explode(',', $user['tuij']);

                $arr = [];
                foreach ($xj as $v) {

                    $user_info = Db::table('user')->where('id', $v)->find();

                    $num = Db::table('order')->where('user_id', $v)->where('status', 1)->count();


                    if ($num == 0) {
                        $tag = 0;
                        $user_info['tag'] = $tag;
                    } else {
                        $tag = 1;
                        $user_info['tag'] = $tag;
                    }

                    array_push($arr, $user_info);
                }

                //没有用户 返回[]数组
                foreach ($xj as $k => $value) {
                    if ($k == 0) {
                        if ($value == '') {
                            $n = count($xj);
                            if ($n == 1) {
                                $arr = [];
                            }
                        }
                    }
                }


                $mach = Db::table('mach')->where('user_id', $user['id'])->where('activate', 0)->order('order_status', 'asc')->order('mach_num', 'asc')->select();

                return Y::json(0, '机器划拨', ['user' => $arr, 'mach' => $mach]);


            } elseif ($tag == 'client') {

                $xj = explode(',', $user['tuij']);


                $arr = [];

                foreach ($xj as $v) {

                    $num = Db::table('mach')->where('user_id', $v)->count();
                    if ($num == 0) {

                        $user_info = Db::table('user')->where('id', $v)->find();

                        array_push($arr, $user_info);
                    }

                }


                $mach = Db::table('mach')->where('user_id', $user['id'])->where('activate', 0)->order('order_status', 'asc')->order('mach_num', 'asc')->select();


                return Y::json(0, '机器划拨', ['user' => $arr, 'mach' => $mach]);

            }

        } else {

            //agent 代理商   client 客户
            $tag = input('tag', '');

            if ($tag == 'agent') {

                $ids = input('pos_id', ''); //pos机id
                $user_id = input('user_id', '');


                $ids = explode(',', $ids); //分割为数组

                $num_count = count($ids);

                $num = input('num', $num_count);

                $address = input('address', '下级代理划拨');
                $money = input('money', '1');//下级划拨 都是1

                $user = Db::table('user')->where('id', $user_id)->find();  //查询用户


                $order_status = db('order')->where('user_id', $user['id'])->where('status', 1)->count();  //　查询有没有过订单


                if ($order_status > 0) {

                    $order = Order::build_order_no('R');
                    $order_time = date('Y-m-d H:i:s');
                    $result = Db::table('order')->insert(['user_id' => $user['id'], 'num' => $num, 'address' => $address, 'money' => $money, 'status' => 1, 'order' => $order, 'order_time' => $order_time, 'order_status' => 1, 'level' => 1, 'sendStatus' => 1, 'payType' => 3]);
                    $m = Db::table('cashback')->where('type', 'order2')->value('money');

                    foreach ($ids as $val) {
                        Db::table('mach')->where('mach_num', $val)->update(['user_id' => $user['id'], 'ap' => $user['nickname'], 'phone' => $user['mobile'], 'activite_pay' => $m, 'order_status' => 1]);
                    }

                } else {

                    $order = Order::build_order_no('R');
                    $order_time = date('Y-m-d H:i:s');
                    $result = Db::table('order')->insert(['user_id' => $user['id'], 'num' => $num, 'address' => $address, 'money' => $money, 'status' => 1, 'order' => $order, 'order_time' => $order_time, 'order_status' => 0, 'level' => 1, 'sendStatus' => 1, 'payType' => 3]);
                    $m = Db::table('cashback')->where('type', 'order1')->value('money');
                    foreach ($ids as $k => $val) {
                        if ($k <= 9) {
                            Db::table('mach')->where('mach_num', $val)->update(['user_id' => $user['id'], 'ap' => $user['nickname'], 'phone' => $user['mobile'], 'activite_pay' => $m, 'order_status' => 0, 'hb_status' => 0]);
                        } else {
                            Db::table('mach')->where('mach_num', $val)->update(['user_id' => $user['id'], 'ap' => $user['nickname'], 'phone' => $user['mobile'], 'activite_pay' => $m, 'order_status' => 0, 'hb_status' => 1]);
                        }
                    }
                    $m = Db::table('cashback')->where('type', 'invite')->value('money');

                    $pid = Db::table('user')->where('id', $user_id)->value('parend_id');

                    $p_money = Db::table('purse')->where('user_id', $pid)->order('id', 'desc')->value('total_money');

                    $p_total_money = bcadd($p_money, $m, 2);

                    $result = Db::table('purse')->insert(['user_id' => $pid, 'invite' => $m, 'invite_user' => $user_id, 'total_money' => $p_total_money, 'trade_time' => date('Y-m-d H:i:s'), 'create_time' => date('Y-m-d H:i:s')]);

                    $jb = Db::table('user')->where('id', $user_id)->value('hhr_jb');

                    if ($jb == 'K') {
                        Db::table('user')->where('id', $user_id)->setField('hhr_jb', 'A');
                        Db::table('chaoji')->where('user_id', $user_id)->update(['b' => null, 'die_time' => null]);
                    }


                }
                if ($result) {
                    return Y::json(0, '划拨成功');
                }


            } elseif ($tag == 'client') {

                $pos_id = input('pos_id', ''); //pos机id
                $user_id = input('user_id', '');


                $user = Db::table('user')->where('id', $user_id)->find();  //查询用户

                if ($user['hhr_jb'] == 'K') {

                    return Y::json(1, '体验合伙人只能划拨一次');

                }

                //修改为等级为K 体验合伙人
                Db::table('user')->where('id', $user_id)->setField('hhr_jb', 'K');
                Db::table('mach')->where('mach_num', $pos_id)->update(['user_id' => $user['id'], 'ap' => $user['nickname'], 'phone' => $user['mobile'], 'order_status' => 0, 'hb_status' => 0]);

                $result = Db::table('chaoji')->where('user_id', $user_id)->find();

                if ($result) {
                    $res = Db::table('chaoji')->where('user_id', $user_id)->update(['b' => 'K', 'die_time' => '2099-12-30']);
                } else {
                    $res = Db::table('chaoji')->insert(['user_id' => $user_id, 'u' => $user['nickname'], 't' => $user['mobile'], 'b' => 'K', 'yb' => 'A', 'die_time' => '2099-12-30']);
                }


                if ($res) {
                    return Y::json(0, '划拨成功');
                } else {
                    return Y::json(1, '划拨失败.....');
                }
            }

        }
    }

    /**
     * 昨日团队交易
     */
    public function countRevenue()
    {

        $user = app()->user;

        $xjhhr = Db::table('user')->where('id', $user['id'])->value('xjhhr');

        $team = explode(',', $xjhhr);

        $num = 0;

        foreach ($team as $value) {

            $m = Db::table('trade')->where('user_id', $value)->whereTime('trade_time', 'yesterday')->field('sum(meny)')->group('user_id')->find();

            $num = bcadd($num, $m['sum(meny)'], 2);

        }

        return Y::json(0, '昨日团队交易', ['num' => $num]);
    }

    /**
     * 今日收益
     */
    public function dayRevenue()
    {
        $user = app()->user;


        $data = Db::table('purse')->where('user_id', $user['id'])->whereTime('create_time', 'today')->select();

        $num = 0;
        foreach ($data as $v) {

            $num = bcadd($num, $v['invite'], 2);
            $num = bcadd($num, $v['pos_jh'], 2);
            $num = bcadd($num, $v['zy_share'], 2);
            $num = bcadd($num, $v['team_share'], 2);

        }

        return Y::json(0, '今日收益', ['num' => $num]);

    }

    //上传头像
    public function face()
    {
        if (request()->isGet()) {
//            $user = app()->user;
            $auth = Auth::instance();
//            $auth->clear($user['id']);
            $user = app()->user;

            $token = $auth->token($user['id']);
            $user['token'] = $token;


            $pid = Db::table('user')->where('id', $user['id'])->value('parend_id');
            $user_info = Db::table('user')->where('id', $pid)->value('nickname');

            $user['face'] = Y::get_img($user['face']);

            $user['parend_id'] = $user_info;

            if ($user) {
                return Y::json(0, '用户信息', $user);
            }

        } elseif (request()->isPost()) {

            Cache::clear();

            $file = $_FILES;
            $user = app()->user;

            if (!empty($user['face'])) {

                $face = '.' . $user['face'];

                unlink($face);

            }


            if (!empty($file['cover'])) {
                $data['cover'] = $file['cover'];
            }

            if (!empty($data['cover'])) {
                $img_path = Upload::upload1('face', $data['cover']);

                $img_path = substr($img_path, strpos($img_path, '/upload'));
                $result = db('user')->where('id', $user['id'])->update(['face' => $img_path]);

                if (!$result) {
                    return Y::json('1', '上传失败');
                } else {
                    return Y::json(0, '上传成功');
                }
            }

        }
    }

    //分润
    public function share()
    {
        $user = app()->user;

        $data = Db::table('share')->where('user_id', $user['id'])->field('user_id,sum(share),sum(team_share),trade_time')->group('trade_time')->order('trade_time', 'desc')->select();


        foreach ($data as &$v) {

            if (!empty($v['sum(share)'])) {
                $v['share_total'] = bcadd($v['sum(share)'], $v['sum(team_share)'], 2);
            } else {
                $v['sum(share)'] = 0;
                $v['share_total'] = bcadd($v['sum(share)'], $v['sum(team_share)'], 2);
            }

            if (empty($v['sum(team_share)'])) {
                $v['sum(team_share)'] = 0;
            }

        }


        if ($data) {
            return Y::json(1, '分润金额', $data);
        } else {
            return Y::json(0, '获取失败');
        }

    }

    //我的本月总收益
    public function sumPurse()
    {

        $user = app()->user;

        $data = Db::table('purse')->where('user_id', $user['id'])->whereTime('trade_time', 'month')
            ->field('user_id,sum(invite),sum(pos_jh),sum(zy_share),sum(team_share)')->group('invite,pos_jh,zy_share,team_share')->select();

        $m = 0;
        foreach ($data as $v) {
            $m += $v['sum(invite)'];
            $m += $v['sum(pos_jh)'];
            $m += $v['sum(zy_share)'];
            $m += $v['sum(team_share)'];

        }

        return Y::json(1, '所有收益', ['money' => $m]);

    }

    /**
     * 提现记录
     */
    public function txList()
    {

        $user = app()->user;

        $data = Db::table('tx')->where('user_id', $user['id'])->order('create_time', 'desc')->select();

        return Y::json(0, '提现记录', $data);

    }


    /**
     * 我的商户
     */
    public function mich()
    {
        $user = app()->user;

        $data = Db::table('mach')->where('user_id', $user['id'])->field('user_id,mach_num,activite_time')->order('activite_time', 'desc')->select();

        foreach ($data as &$v) {
            $v['money'] = Db::table('trade')->where('pos_id', $v['mach_num'])->whereTime('trade_time', 'month')->sum('meny');
            $v['mich'] = Db::table('trade')->where('pos_id', $v['mach_num'])->value('mich');
            $v['reach'] = Db::table('trade')->where('pos_id', $v['mach_num'])->order('id', 'desc')->value('reach');
        }

        $result = [];

        foreach ($data as $vn) {
            if (!empty($vn['mich'])) {
                array_push($result, $vn);
            }
        }

        return Y::json(0, '我的商户', $result);
    }
}
