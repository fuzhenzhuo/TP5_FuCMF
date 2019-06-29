<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/2/5
 * Time: 10:32
 */

namespace app\common\library;
use think\Db;
class Y
{
    /**
     * 返回数据格式化
     * @return mixed
     */
    public static function json()
    {
        $res       = new \stdClass();
        $res->code = 0;
        $res->msg  = 'success';
        $res->data = new \stdClass();

        $field = func_get_args();

        switch (count($field)) {
            case 0:
                break;
            case 1:
                if (is_scalar($field[0])) {
                    $res->msg = $field[0];
                } else {
                    $res->data = $field[0];
                }
                break;
            case 2:
                $res->code = $field[0];
                $res->msg  = $field[1];
                break;
            case 3:
                $res->code = $field[0];
                $res->msg  = $field[1];
                $res->data = $field[2];
                break;
            default:
                $res->code = 500;
                $res->msg  = 'fail';
        }

        $res->code = intval($res->code);

        //将返回结果统一为字符串，方便客户端操作
        if (!empty($res->data)) {
            if (!is_array($res->data)) {
                $res->data = json_decode(json_encode($res->data), true);
            }
            array_walk_recursive($res->data, function (&$val) {
                $val = strval($val);
            });
        }
        $code = $res->code >= 200 && $res->code <= 500 ? $res->code : 200;
        return json($res, $code)->send();
    }

    /**
     * 图片字符穿返回图片
     * @param unknown $img_arr
     */
    public  static final function get_img($img_arr){
        if(is_array($img_arr)){
            foreach ($img_arr as $key=>$val){
                $img_arr[$key] = "http://".$_SERVER['HTTP_HOST'].$val;
            }
        }else{
            $img_arr = "http://".$_SERVER['HTTP_HOST'].$img_arr;
        }
        return $img_arr;
    }

    /**
     * 检查文件是否存在
     * @param $file_http_path       文件完整路径 带http或者https
     * @return bool
     */
    public static final function check_exists($file_http_path){
        // 远程文件
        if(strtolower(substr($file_http_path, 0, 4))=='http'){

            $header = get_headers($file_http_path, true);

            return isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'));
            // 本地文件
        }else{
            return file_exists('.'.$file_http_path);
        }
    }



    public  static final function encrypt_md5($param="",$key=""){
        #对数组进行排序拼接
        if(is_array($param)){
            $md5Str = implode($this->arrSort($param));
        }
        else{
            $md5Str = $param;
        }
        $md5 = md5($md5Str . $key);
        return '' === $param ? 'false' : $md5;
    }

    /**
     * 验证token
     */
    public  static final function check_token($token, $user_id){
        return $token == encrypt_md5($user_id,"baowen") ? true : false;
    }

    /**
     * @param $lng
     * @param $lat
     * @param float $distance 单位：km
     * @return array
     * 根据传入的经纬度，和距离范围，返回所有在距离范围内的经纬度的取值范围
     */
    public static final function location_range($lng, $lat, $distance = 0.5)
    {
        $earthRadius = 6378.137;//单位km
        $d_lng = 2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $d_lng = rad2deg($d_lng);
        $d_lat = $distance / $earthRadius;
        $d_lat = rad2deg($d_lat);
        return array(
            'lat_start' => $lat - $d_lat,//纬度开始
            'lat_end' => $lat + $d_lat,//纬度结束
            'lng_start' => $lng - $d_lng,//纬度开始
            'lng_end' => $lng + $d_lng//纬度结束
        );
    }

    /**
     * @param $arc_id   文章id
     * @param int $comm_id   评论id
     * @param array $result
     * @return array
     */

    public static final function getCommlist($arc_id, $comm_id = 0, &$result = array()){
        //获取评论列表
        if(empty($arc_id)){
            return array();
        }
        $_where = "article_id = {$arc_id} AND comment_id = {$comm_id}";
        $res =Db::table('comment') ->where($_where)->order('create_time','desc')->select();
        if(empty($res)){
            return array();
        }

        foreach ($res as $cm) {
            $thisArr = &$result[];
            $cm["_child"] =Y::getCommlist($arc_id,$cm['id'],$thisArr);
            $thisArr = $cm;
        }

        return $result;
    }

}