<?php

namespace app\admin\controller\msg;

use function MongoDB\BSON\fromJSON;
use think\Controller;
use think\Db;
use think\Request;
use app\common\library\Y;
use app\admin\model\Article;
class MsgController extends Controller
{

    private $appKey = 'pvxdm17jpo3dr';
    private $appSecret = 'wto8wDFqol6';

    const   SERVERAPIURL = 'http://api.cn.ronghub.com';    //IM服务地址
    const   SMSURL = 'http://api.sms.ronghub.com';          //短信服务地址

    public function index()
    {
        return view();
    }

    public function push()
    {
        $content =input('content','');
        $type = input('type','');
        if ($type == 0){
            $result = [
                'platform'=> ['ios','android'],
                'fromuserid' => '__system__',
                'audience' => ['is_to_all'=>true],
                'message' => [
                    'content'=> json_encode(['content' => $content, 'extra' => 'aaa']),
                    'objectName' => 'RC:TxtMsg',
                ],
                "notification"=>["alert"=>"系统消息"]
            ];

            $data =  $this->curl('/push.json',$result,'json','im','POST');
        }elseif ($type ==1){
            $result = [
                'platform'=> ['ios','android'],
                'audience' => ['is_to_all'=>true],
                "notification"=>["alert"=>"系统消息:$content"]
            ];

            $data =  $this->curl('/push.json',$result,'json','im','POST');
        }


       return Y::json($data);

    }

    public function PushArticle()
    {
        $id = input('id','');

        $data = Article::alias('a')
            ->leftJoin('user b', 'a.user_id = b.id')
            ->field('a.*,b.nickname,b.mobile,b.address')
            ->with('cover')
            ->find($id);
        foreach ( $data['cover'] as &$v){
            $v['address'] =Y::get_img($v['address']);
            unset($v['id']);
            unset($v['article_id']);
        }

        $content = "用户{$data['nickname']}，发布了一篇关于{$data['title']}的信息，内容是：{$data['content']},他的联系方式：{$data['mobile']}，地址：{$data['address']}";

        $result = [
            'platform'=> ['ios','android'],
            'fromuserid' => '__system__',
            'audience' => ['is_to_all'=>true],
            'message' => [
                'content'=> json_encode(['content' => $content, 'extra' => 'aaa']),
                'objectName' => 'RC:TxtMsg',
            ],
            "notification"=>["alert"=>"系统消息"]
        ];

        $data =  $this->curl('/push.json',$result,'json','im','POST');

        return Y::json($data);

    }

    public function PushDel()
    {
        $content =input('message','');
        $id = $this->request->get('id', 0, 'intval');

        $data = Article::find($id);

        if ($id > 0 && Db::name('article')->where('id', $id)->setField('status', 2) !== false) {

            $result = [
                'platform'=> ['ios','android'],
                'audience' => [
                    'userid'=>["{$data['user_id']}"],
                    'is_to_all'=>false
                ],
                "notification"=>["alert"=>"系统消息:$content"]
            ];

            $data =  $this->curl('/push.json',$result,'json','im','POST');

            return Y::json($data);
        }
        $this->error('删除失败');


//
//        $result = [
//            'platform'=> ['ios','android'],
//            'audience' => [
//                'userid'=>["{$data['user_id']}"],
//                'is_to_all'=>false
//            ],
//            "notification"=>["alert"=>"系统消息:$content"]
//        ];
//
//        $data =  $this->curl('/push.json',$result,'json','im','POST');
//
//        return Y::json($data);


    }




    /**
     * 发起 server 请求
     * @param $action
     * @param $params
     * @param $httpHeader
     * @return mixed
     */
    public function curl($action, $params, $contentType = 'json', $module = 'im', $httpMethod = 'POST')
    {
        switch ($module) {
            case 'im':
                $action = self::SERVERAPIURL . $action;
                break;
            case 'sms':
                $action = self::SMSURL . $action;
                break;
            default:
                $action = self::SERVERAPIURL;
        }
        $httpHeader = $this->createHttpHeader();
        $ch = curl_init();
        if ($httpMethod == 'POST' && $contentType == 'urlencoded') {
            $httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($params));
        }
        if ($httpMethod == 'POST' && $contentType == 'json') {
            $httpHeader[] = 'Content-Type:Application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        if ($httpMethod == 'GET' && $contentType == 'urlencoded') {
            $action .= strpos($action, '?') === false ? '?' : '&';
            $action .= $this->build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, $httpMethod == 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret = curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }

    /**
     * 创建http header参数
     * @param array $data
     * @return bool
     */
    private function createHttpHeader()
    {
        $nonce = mt_rand();
        $timeStamp = time();
        $sign = sha1($this->appSecret . $nonce . $timeStamp);
        return array(
            'RC-App-Key:' . $this->appKey,
            'RC-Nonce:' . $nonce,
            'RC-Timestamp:' . $timeStamp,
            'RC-Signature:' . $sign,
        );
    }

}
