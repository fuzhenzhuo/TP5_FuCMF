<?php

namespace app\admin\controller;

use app\common\library\Y;
use think\Controller;
use think\Db;
use think\Request;
use app\common\library\Aes;
use app\common\curl_request;
class TestController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view();
    }

    public function lists()
    {


        //  同步user用户表
//        $data =Db::table('user_info1')->select();
//        foreach ($data as $v){
//            Db::table('user')->insert(['id'=>$v['id'],'nickname'=>$v['user_name'],'mobile'=>$v['user_tel'],'id_card'=>$v['sfz_id'],'password'=>$v['user_pass'],'txpass'=>$v['zfpass'],
//                'bank_card'=>$v['yhk_numbs'],'bank_name'=>$v['kh_name'],'address'=>$v['user_add'],'register_time'=>$v['reg_date'],
//                'tuij'=>$v['btjr_name'],'parend_id'=>$v['tjr_name'],'xjhhr'=>$v['xjhhr'],'fenrun_gx'=>$v['fenrun_gx']]);
//
//        }

        //-------------------------------------------------------------------------------------------


        //同步机器表

//
//        $data =Db::table('jiju_list')->select();
//
//        foreach ($data as $v){
//            if ( $v['sshhr'] =='----'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['sshhr'=>'']);
//            }
//
//            if ($v['xb_tel'] == '----'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['xb_tel'=>'']);
//            }
//            if ($v['xb_bool'] == '未下拨'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['xb_bool'=>'0']);
//            }else if($v['xb_bool'] == '已下拨'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['xb_bool'=>'1']);
//            }
//
//            if ($v['xb_date'] == '----'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['xb_date'=>'']);
//            }
//
//            if ($v['bool_active'] == '未激活'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['bool_active'=>0]);
//            }elseif($v['bool_active'] == '是'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['bool_active'=>1]);
//            }
//
//            if ($v['jh_date'] == '----'){
//                Db::table('jiju_list')->where('id',$v['id'])->update(['jh_date'=>'']);
//            }
//
//        }


//
//        $result =Db::table('jiju_list')->select();
//
//        foreach ($result as $val){
//
//            if (!empty($val['xb_date'])){
//                $t1 = '20'.$val['xb_date'];
//
//            }else{
//                $t1 =null;
//            }
//
//            if (!empty($val['jh_date'])){
//                $t2 =$val['jh_date'];
//            }else{
//                $t2 =null;
//            }
//
//
//            Db::table('mach')->insert(['mach_num'=>$val['jiju_numbers'],'ap'=>$val['sshhr'],'send_down'=>$val['xb_bool'],'phone'=>$val['xb_tel']
//                ,'down_time'=>$t1,'activate'=>$val['bool_active'],'activite_time'=>$t2,'jy_je'=>$val['jy_je'],'activite_pay'=>$val['jh_je']]);
//        }


        // 同步机器 用户id 手机号
//        $data = Db::table('mach')->select();
//
//        foreach ($data as $value) {
//            if (!empty($value['phone'])){
//                $id = Db::table('user')->where('mobile', $value['phone'])->value('id');
//                      Db::table('mach')->where('id',$value['id'])->setField('user_id',$id);
//
//            }
//
//        }

//


        //同步超级合伙人表

//        $user = Db::table('user')->select();
//
//        $data = Db::table('chaoji')->select();
//
//
//            foreach ($data as $val){
//               $id =Db::table('user')->where('mobile',$val['t'])->value('id');
//               Db::table('chaoji')->where('id',$val['id'])->setField('user_id',$id);
//            }

//
        //更新 机具表的user_id
//        $res = Db::table('mach')->select();
//
//        foreach ($res as $v){
//            if (!empty($v['phone'])){
//              $id =  Db::table('user')->where('mobile',$v['phone'])->value('id');
//              Db::table('mach')->where('phone',$v['phone'])->update(['user_id'=>$id]);
//            }
//
//        }


//
//        foreach ($tuij as $vn) {
//
//            $jb = Db::table('user')->where('id', $vn)->value('hhr_jb');
//
//            $bl = Db::table('level')->where('abc', $jb)->value('fr_bl');
//
//            $bl = rtrim($bl, '%');
//
//            if ($user_bl > $bl) {
//
//                $x =  bcsub($user_bl, $bl, 3);
//                $do = substr($x, strripos($x, ".") + 1);
//
//                $do = '0.00' . $do;
//                $time = $vn.'.'.date('Y-04');
//
//                $m = Db::table('team_jy')->where('user_id',$vn)->whereTime('utime',$time)->value('td_jy');
//                dump($m);die;
//                $run = bcmul($m, $do, 2);
//                dump($run);die;
//
//            }


        //分润

//        Db::query(" truncate table share_temp");
//
//        $time = date('Y-m-d');
//
//        $trade = Db::table('trade')->whereTime('create_time', $time)->select();
//
//        foreach ($trade as $v) {
//
//            $ids = Db::table('user')->where('id', $v['user_id'])->value('fenrun_gx');
//
//            $user_ids = explode(',', $ids);
//
//            rsort($user_ids);
//
//
//            foreach ($user_ids as $k => $id) {
//
//                if ($k == 0) {
//
//
//                    $jb = Db::table('user')->where('id', $id)->value('hhr_jb');
//
//                    $bl = Db::table('level')->where('abc', $jb)->value('fr_bl');
//
//                    $abc = Db::table('level')->order('id', 'desc')->value('abc');
//
//
//                    if ($jb == $abc) {
//
//                        $bl = rtrim($bl, '%');
//                        $do = substr($bl, strripos($bl, ".") + 1);
//                        $do = '0.00' . $do;
//
//                        $m = bcmul($v['meny'], $do, 2);
//
//                        Db::table('share')->insert(['user_id' => $id, 'mach_id' => $v['pos_id'], 'share' => $m, 'ratio' => $do, 'trade_money' => $v['meny'], 'trade_time' => $v['trade_time'], 'create_time' => date('Y-m-d H:i:s')]);
//
//                        continue;
//                    } else {
//
//                        $bl = rtrim($bl, '%');
//                        $do = substr($bl, strripos($bl, ".") + 1);
//                        $do = '0.00' . $do;
//
//                        $m = bcmul($v['meny'], $do, 2);
//
//                        Db::table('share')->insert(['user_id' => $id, 'mach_id' => $v['pos_id'], 'share' => $m, 'ratio' => $do, 'trade_money' => $v['meny'], 'trade_time' => $v['trade_time'], 'create_time' => date('Y-m-d H:i:s')]);
//                    }
//
//                } else {
//                    $k1 = '';
//                    $k1 = $k - 1;
//
//                    if (array_key_exists($k1, $user_ids)) {
//                        $xj_id = $user_ids[$k1];
//                    }
//
//                    $arr = [];
//                    array_push($arr, $xj_id);
//
//                    $sj_jb = Db::table('user')->where('id', $id)->value('hhr_jb'); //上级  级别
//
//                    $sj_bl = Db::table('level')->where('abc', $sj_jb)->value('fr_bl');  //上级 分润比例
//
//                    $sj_bl = rtrim($sj_bl, '%');
//                    $sj_do = substr($sj_bl, strripos($sj_bl, ".") + 1);
//                    $sj_do = '0.00' . $sj_do;
//
//
//                    $xj_jb = Db::table('user')->where('id', $xj_id)->value('hhr_jb'); //下级  级别
//
//                    $xj_bl = Db::table('level')->where('abc', $xj_jb)->value('fr_bl');  //下级 分润比例
//
//                    $xj_bl = rtrim($xj_bl, '%');
//                    $xj_do = substr($xj_bl, strripos($xj_bl, ".") + 1);
//                    $xj_do = '0.00' . $xj_do;
//
//
//                    $abc = Db::table('level')->order('id', 'desc')->value('abc');
//
//                    foreach ($arr as $i) {
//
//                        $i_jb = Db::table('user')->where('id', $i)->value('hhr_jb');
//
//                        $arr1 = [];
//                        array_push($arr1, $i_jb);
//                    }
//                    if (in_array($abc, $arr1)) {
//                        break;
//                    } else {
//                        if ($sj_do > $xj_do) {
//
//                            $x = bcsub($sj_do, $xj_do, 4);
//
//                            $run = bcmul($v['meny'], $x, 2);
//
//                            Db::table('share')->insert(['user_id' => $id, 'mach_id' => $v['pos_id'], 'team_share' => $run, 'ratio' => $x, 'trade_money' => $v['meny'], 'trade_time' => $v['trade_time'], 'create_time' => date('Y-m-d H:i:s')]);
//                        }
//                    }
//
//                }
//
//            }
//
//        }
//
//            $data =  Db::table('share')->field('user_id,sum(share),sum(team_share)')->group('user_id')->select();
//
//            foreach ($data as $dt){
//
//                 $m = Db::table('purse')->where('user_id',$dt['user_id'])->order('id','desc')->value('total_money');
//
//                 $share_m = bcadd($m,$dt['sum(share)'],2);
//
//                 $total_m =  bcadd($dt['sum(team_share)'],$share_m,2);
//
//                 Db::table('purse')->insert(['user_id'=>$dt['user_id'],'zy_share'=>$dt['sum(share)'],'team_share'=>$dt['sum(team_share)'],'total_money'=>$total_m]);
//            }




//        $token = '172b638017259aa4f3265334ad0aa16b';
//        $key = 'f10adc3639ba59oijh56e057f20f963e';
//        $url ='https://testxiniu.kakahui.net/union-h5-client/api/v1/creditcard/apply';
//
//        $t = $this->get_total_millisecond();
//
//        $mach_id = 'M10061';
//
//        $rad = 9999;
//        $order = $mach_id . $t . $rad;
//
//        $array['merchantId'] = $mach_id;
//        $array['userCertNo'] = '131002199311091514';
//        $array['userMobile'] = '13717904069';
//        $array['notityUrlOrder']='www.baidu.com';
//        $array['channelCode'] = 'C1010005';
//        $array['merchantOrderId'] = $order;
//        $array['userName'] = 'syk';
//
////        ksort($array);
//
//        $data = json_encode($array);
////        dump($data);die;
//
//        $sign =hash('sha256',$data);
//
//        $result = $this->encrypt($data,$key);
//
////        $res = $this->decrypt($result,$key);
//
//        $info=$this->curl_request($url,'post',$result);
//
//        dump($info);die;
//
//    }
//
////可以发送get和post的请求方式
//    function curl_request($url,$method='get',$data=null,$https=true,$sign=null){
//
////        $header =[
////            'token'=>'172b638017259aa4f3265334ad0aa16b',
////            'timestamp' => $this->get_total_millisecond(),
////            'sign' => $sign,
////        ];
//
//        //1.初识化curl
//        $ch = curl_init($url);
//        //2.根据实际请求需求进行参数封装
//        //返回数据不直接输出
//            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//
//        //如果是https请求
//        if($https === true){
//            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
//            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
//        }
//        //如果是post请求
//        if($method === 'post'){
//            //开启发送post请求选项
////            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//            curl_setopt($ch,CURLOPT_POST,true);
//            //发送post的数据
//            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
////            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        }
//        //3.发送请求
//        $result = curl_exec($ch);
//        //4.返回返回值，关闭连接
//        curl_close($ch);
//        return $result;
//    }
//
//    function encrypt($string, $key)
//    {
//
//        // 对接java，服务商做的AES加密通过SHA1PRNG算法（只要password一样，每次生成的数组都是一样的），Java的加密源码翻译php如下：
//        $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
//
//        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
//        $data = openssl_encrypt($string, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
//
////        $data = strtolower(bin2hex($data));
//        $data = strtolower(base64_encode($data));
//
//        return $data;
//    }
//
//
//    /**
//     * @param string $string 需要解密的字符串
//     * @param string $key 密钥
//     * @return string
//     */
//    function decrypt($string, $key)
//    {
//        // 对接java，服务商做的AES加密通过SHA1PRNG算法（只要password一样，每次生成的数组都是一样的），Java的加密源码翻译php如下：
//        $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
//
////        $decrypted = openssl_decrypt(hex2bin($string), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
//        $decrypted = openssl_decrypt(base64_decode($string), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
//
//        return $decrypted;
//    }
//
//     function get_total_millisecond()
//    {
//        $time = explode (" ", microtime () );
//        $time = $time [1] . ($time [0] * 1000);
//        $time2 = explode ( ".", $time );
//        $time = $time2 [0];
//        return $time;




//        Db::table('mach')->where('hb_status',null)->setField('hb_status',1);




















    }


}
