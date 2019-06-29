<?php
/**
 * Created by PhpStorm.
 * User: Y.c
 * Date: 2017/6/20
 * Time: 14:53
 */

namespace app\admin\controller;

use app\common\model\User;
use function GuzzleHttp\default_user_agent;
use think\Db;
use app\common\library\Y;

class UserController extends BaseController
{

    public function index()
    {
        return view();
    }

    public function lists()
    {
        $status = input('status', '-1', 'intval');

        $keyword = $this->request->post('keyword', '', 'trim');

        $range_time = $this->request->post('range_time', '', 'trim');

        $real =input('real','');
        $jb =input('jb','');

        $list_rows = input('list_rows', 20);

        $userModel = Db::table('user')->alias('a')
            ->leftJoin('cert_pig b', 'a.id = b.pid')
            ->field('a.*,b.cove1,b.cove2')
            ->when(!empty($keyword), function ($query) use ($keyword) {
                return $query->where('mobile|nickname', 'like', '%' . $keyword . '%');
            })
            ->when(!empty($range_time), function ($query) use ($range_time) {
                $range_time = range_time($range_time);

                return $query->where('register_time', 'between', [date('Y-m-d', $range_time[0]), date('Y-m-d', $range_time[1])]);
            })->when(isset($status), function ($query) use ($status) {
                if ($status > -1) {
                    return $query->where('status', $status)->order('id', 'desc');
                }
                if ($status == -1) {
                    return $query->order('id', 'desc');
                }
            })->when(isset($real), function ($query) use ($real) {
                if ($real > -1) {
                    return $query->where('real', $real);
                }
            })->when(isset($jb), function ($query) use ($jb) {
                if ($jb > -1) {
                    return $query->where('hhr_jb', $jb);
                }
            })
            ->paginate($list_rows, false, ['query' => request()->param()])
            ->each(function (&$v) {
                $v['face'] = Y::get_img($v['face']);
                $v['cove1'] = Y::get_img($v['cove1']);
                $v['cove2'] = Y::get_img($v['cove2']);
                $v['total_money'] =Db::table('purse')->where('user_id',$v['id'])->order('id','desc')->value('total_money');
                $v['parend_id'] = Db::table('user')->where('id', $v['parend_id'])->find();

                return $v;
            });


        $page = $userModel->render();

        return view('lists', ['list' => $userModel, 'page' => $page]);
    }

    /**
     * 快速用户认证
     * @return json
     */
    public function setReal()
    {

        $id = input('user_id', 0, 'intval');

        $status = input('status', 0, 'intval');

        $msg = input('message','');

        if ($id > 0 && Db::name('user')->where('id', $id)->update(['real'=>$status,'msg'=>$msg]) > 0) {

//            $this->success('设置成功');
            return Y::json(0, '设置成功');
        }

        $this->error('更新失败');
    }

    /**
     * 快速禁用
     * @return json
     */
    public function setStatus()
    {
        $id = $this->request->get('id', 0, 'intval');
        $status = $this->request->get('status', 0, 'intval');

        if ($id > 0 && Db::name('user')->where('id', $id)->setField('status', $status) > 0) {
            $this->success('设置成功');
        }

        $this->error('更新失败');
    }


    public function edit()
    {

        if ($this->request->isPost()) {
            $id = input('id', '');
            $level = input('level', '');
            $deadtime = input('deadtime', '');


            $user = Db::table('user')->where('id', $id)->find();

            if ($level == 1) {



                $yb = Db::table('chaoji')->where('user_id', $id)->value('yb');

                Db::table('chaoji')->where('user_id',$id)->setField('b',$yb);

                $result = Db::table('user')->where('id', $id)->update(['hhr_jb' => $yb]);


                if ($result) {
                    $this->success('修改成功');
                }

            } else {


                $hhr_jb = Db::table('user')->where('id',$id)->value('hhr_jb');

                if (Db::table('user')->where('id', $id)->setField('hhr_jb', $level) > 0) {

                    $info = Db::table('chaoji')->where('user_id',$id)->find();
                    if ($info){
                        $result = Db::table('chaoji')->where('user_id', $id)->update(['b' => $level, 'die_time' => $deadtime]);
                    }else{
                        $p_user =Db::table('user')->where('id',$id)->field('nickname,mobile')->find();

                        $result = Db::table('chaoji')->where('user_id', $id)->insert(['user_id'=>$id,'u'=>$p_user['nickname'],'t'=>$p_user['mobile'],'b' => $level,'yb'=>$hhr_jb, 'die_time' => $deadtime]);
                    }


                    if ($result) {
                        $this->success('修改成功');
                    }

                }

            }

            return Y::json(1, '修改失败');
        } else {
            $id = $this->request->get('id', 0, 'intval');
            $user = User::get($id);
            $this->assign('user', $user);
            return view();
        }
    }


    public function auth()
    {
        return view();
    }

    public function auth_list()
    {

        $d1 = date('d');

        if ($d1 != 01) {
            echo "<script>alert('每个月一号才能刷新')</script>";
            die;
        }

        $data4 = DB::table('level')->order('id', 'desc')->select();

        $jb_arr = [];
        foreach ($data4 as $v) {

            $dq_lev_name = $v['names'];  //当前级别名称
            $yj = $v['td_yj'];        //级别交易
            $rs = $v['tj_syj'];       //上级人数
            $abc = $v['abc'];
            if ($rs == 0) {
                $lev_name = $v['names'];
                $str = $dq_lev_name . ',' . $yj . ',' . $abc . ',' . $rs . ',';
                array_push($jb_arr, $str);
            } else {
                $new_arr = [];
                $dq_id = $v['id'];        //当前id
                $sjhhr = $dq_id + $v['shang'];     //上调几级后对应的id
                $data5 = DB::table('level')->where('id', $sjhhr)->select();

                foreach ($data5 as $value) {
                    $lev_name = $value['abc'];
                    $str2 = $dq_lev_name . ',' . $yj . ',' . $lev_name . ',' . $rs . ',';
                    // array_push($new_arr,$dq_lev_name,$rs,$lev_name);
                    array_push($jb_arr, $str2);
                }

            }
        }


        //print_r($jb_arr);
        //调取数据
//        $sql9 = "select * from user_info1 order by id desc";
        $data = Db::table('user')->order('id', 'desc')->select();

        foreach ($data as $arr) {
            date_default_timezone_set('Asia/Shanghai');
            $t = time();
            $t1 = date('Y-m', strtotime('- 1 month', $t));  //上个月
            $t2 = $arr['id'] . '.' . $t1;

            //团队业绩
            $y_tel = $arr['mobile'];
            $data1 = Db::table('team_jy')->where('phone', $arr['mobile'])->where('utime', $t2)->select();
            foreach ($data1 as $arr1) {

                $jysl = $arr1['td_jy'];
            }
            if (empty($data1)) {
                $jysl = 0;
            }

            $td_arr = explode(',', $arr['xjhhr']);

            $n = count($td_arr);
            $str = '';
            $str2 = '';
            $str3 = '';
            for ($i = 0; $i < $n; $i++) {
                $data2 = Db::table('user')->where('id', $td_arr[$i])->select();
                foreach ($data2 as $arr2) {
                    $str = $str . $arr2['hhr_jb'] . ',';
                    $str2 = $str2 . $arr2['cba'] . ',';
                }

            }
            $str3 = $str . $str2;
            $abc = explode(",", $str3);   //团队人等级数组去重复,进abc字段
            array_pop($abc);
            $arr3 = array_unique($abc);

            $arr_temp = $arr3;
            $arr_temp = implode(",", $arr_temp);

            $data3 = Db::table('user')->where('id', $arr['id'])->update(['abcd' => $arr_temp]);
            $d = implode(",", $arr3);
            $data4 = Db::table('user')->where('id', $arr['id'])->update(['abc' => $d]);
            $zttd = $arr['tuij'];//直推团队

            $A = 0;
            $B = 0;
            $C = 0;
            $D = 0;
            $E = 0;
            $F = 0;
            $G = 0;
            $H = 0;

            if ($zttd == '') {
                $rs = 0;
                $this->get($jysl, $A, $B, $C, $D, $E, $F, $G, $H, $jb_arr, $rs, $y_tel);
            } else {

                $zttd = explode(',', $zttd);

                for ($i = 0; $i < count($zttd); $i++) {

                    $ids = $zttd[$i];

                    $data6 = Db::table('user')->where('id', $ids)->select();
                    foreach ($data6 as $arr13) {

                        $abc = $arr13['abcd'];
                        $A += substr_count($abc, 'A');
                        $B += substr_count($abc, 'B');
                        $C += substr_count($abc, 'C');
                        $D += substr_count($abc, 'D');
                        $E += substr_count($abc, 'E');
                        $F += substr_count($abc, 'F');
                        $G += substr_count($abc, 'G');
                        $H += substr_count($abc, 'H');
                    }
                }

                $this->get($jysl, $A, $B, $C, $D, $E, $F, $G, $H, $jb_arr, $rs, $y_tel);

            }

        }

        //跑超级合伙人表
        $chaoji = Db::table('chaoji')->select();


        $user = Db::table('user')->select();
        foreach ($user as $vn) {
            $sql = "REPLACE INTO `chaoji` (`user_id`,`u`,`t`,`yb`) VALUES ({$vn['id']},'{$vn['nickname']}',{$vn['mobile']},'{$vn['hhr_jb']}')";
            Db::execute($sql);
        }

        foreach ($chaoji as $vk) {
            DB::table('user')->where('mobile', $vk['t'])->update(['hhr_jb' => $vk['b']]);
        }

    }

    //设置级别
    public function get($jy, $a, $b, $c, $d, $e, $f, $g, $h, $jb, $hav, $tel)
    {

        $str = '';
        //echo "<script>alert('$hav')</script>";
        foreach ($jb as $key => $value) {  //合伙人什么标准
            $str = $str . $value;
        }
        // dump($str);die;
        $a = explode(",", $str);
        $jb_arr = array_chunk($a, 4);
        array_pop($jb_arr);
        $jb_arr[0][1];
        //dump($jb_arr[0][1]);die;
        //print_r($jb_arr);
        //$hav;
        //echo $b;
        if ($jy >= $jb_arr[0][1] and $d >= 3) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'H', 'cba' => 'H,G,F,E,D,C,B,A']);
            //echo "H";
        } else if ($jy >= $jb_arr[1][1] and $d >= 2) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'G', 'cba' => 'G,F,E,D,C,B,A']);

            //echo "G";
        } else if ($jy >= $jb_arr[2][1] and $d >= 2) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'F', 'cba' => 'F,D,C,B,A']);

            //echo "F";
        } else if ($jy >= $jb_arr[3][1] and $c >= 2) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'E', 'cba' => 'E,D,C,B,A']);

            //echo "E";
        } else if ($jy >= $jb_arr[4][1] and $b >= 2) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'D', 'cba' => 'D,C,B,A']);

            //echo "D";
        } else if ($jy >= $jb_arr[5][1] and $c >= 0) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'C', 'cba' => 'C,B,A']);

            //echo "C";
        } else if ($jy >= $jb_arr[6][1] and $b >= 0) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'B', 'cba' => 'B,A']);
            //echo "B";
        } else if ($jy >= $jb_arr[7][1] and $a >= 0) {

            Db::table('user')->where('mobile', $tel)->update(['hhr_jb' => 'A', 'cba' => 'A']);
            //echo "A";
        }

    }


}