<?php
class YwkOauth{

    public $client_id ;  //商家client_id,平台提供
    public $appsecret ;  //商家密钥，平台提供
    public $platform = "sina" ;  //请求平台数据，目前仅支持sina
    public $sign_type = "md5" ;  //签名加密方式,目前仅支持md5加密方式，默认md5
    public $format = "json" ;  //数据返回格式,目前仅支持json格式
    public $v = "2.0" ;    //版本号
    public $timestamp;
    public $host = "http://bestweshop.dianking.cn/egou/index.php/api/uwuku/";  //请求主机地址请勿修改

    /**
     * @param string $client_id    商家client_id
     * @param string $appsecret    密钥
     */
    public function __construct($client_id, $appsecret ){
        $this->client_id = $client_id ;
        $this->appsecret = $appsecret ;
        $this->timestamp = date("YmdHi", time()) ;
    }

    public function http_post($post_string , $url){
        try{
            $ch = curl_init();
//            $post_string = http_build_query($post_string);  //创建 http_build_query
            curl_setopt($ch, CURLOPT_URL, $url); //设置 Curl URL
            curl_setopt($ch, CURLOPT_POST, TRUE); //设置 http POST请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); //将数据执行POST操作
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            var_dump($url.'?'.$post_string);exit;
            $result = curl_exec($ch);
            curl_close($ch);
        }catch(Exception $e){
            echo "Catch Exception:" . $e->getMessage() ;
        }
        return $result ;
    }

    public function http_get($params=array(), $url) {

        if($params){
            $url_str = "" ;
            foreach ($params as $param=>$param_value){
                $url_str .= $param . "=" . urlencode($param_value) ."&" ;
            }
            $result = file_get_contents($url."?".$url_str) ;
            return $result ;
        }else{
            echo "Param Error: The Param is null." ;
        }
    }
    /**
     * 获取系统级参数
     * @param unknown_type $method
     * @param unknown_type $fields
     * @return string
     */
    public function getSystemParam(){
        $param["uid"] = $this->client_id ;
        $param["format"] = $this->format ;
        $param["v"] = $this->v ;
        $param["timestamp"] = $this->timestamp ;
        $param["sign"] = "" ;
        $param["sign_type"] = $this->sign_type ;
        $param["appscret"] = $this->appsecret;
        $param["platform"] = $this->platform ;
        return $param ;
    }
}
/**
 * API操作类
 */
class YwkClient{
    public $oauth ;
    public $request_type ; //商家请求 api 接口类型 具体请查看个请求sdk方法中命名
    public function __construct($client_id, $appsecret){
        $this->oauth = new YwkOauth($client_id, $appsecret) ;
    }
    /**
     * 执行http请求
     * @param array $param
     * @param string $url
     * @param string $http_method http: GET或者POST方式
     * @param int $return_request_url : 1：针对网页授权获取微信粉丝openid 时，返回请求url，0：其他请求
     * @return result
     */
    public function http($param, $url, $http_method=null,$return_request_url = 0) {
        $sign = $this->sign($param) ;  //生成签名加密串
        $param["sign"] = $sign ;
        unset($param["appscret"]) ;
        $param_str = $this->createLinkstringUrlencode($param, 1,0) ;
        if($return_request_url == 1){
            return $url.'?'.$param_str;
        }else{
            switch ($http_method){
                case "POST" :
                    return $this->oauth->http_post($param_str, $url) ;
                    break ;
                case "GET" :
                    return $this->oauth->http_get($param_str, $url) ;
                    break ;
                default:
                    return $this->oauth->http_post($param_str, $url) ;
                    break ;
            }
        }
    }
    /**
     * 获取签名
     * @param unknown_type $param
     * @return string
     */
    public function sign($param){
        $tmp_param = $this->argSort($param) ;
        $tmp_param_str = $this->createLinkstring($tmp_param) ;
        $sign_param_str = $param["uid"].$tmp_param_str ;
        $sign = strtoupper(md5($sign_param_str)) ;
        return $sign ;
    }
    /**
     * 签名时需要组合的串函数
     * @param unknown_type $param
     * @return string
     */
    public function createLinkString($param){
        unset($param["sign"]) ;
        $arg  = "";
        foreach ($param as $key=>$val) {
            if(strlen($val)>0){
                $arg.=$key.$val ;
            }
        }
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $arg = stripslashes($arg);
        }
        return $arg;
    }
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * @return 排序后的数组
     */
    public function argSort($para) {
        ksort($para, SORT_STRING  | SORT_FLAG_CASE); //按数组key 进行排序保留原来的值
        reset($para);  //把数组内部指针指向第一个元素
        return $para;
    }
    /**
     * v-1.0 版本
     * 生成验证字符串：用户id+请求类型+开始时间+结束时间+用户密钥
     * 此处请勿修改
     * @param string $request_type
     * @param string $start_time
     * @param string $end_time
     * @return md5_string
     */
    public function creat_sign($request_type, $start_time="", $end_time="") {
        if($this->oauth->sign_type=="md5"){
            $sign_str = md5($this->oauth->client_id.$request_type.$start_time.$end_time.$this->oauth->appsecret) ;
        }
        return $sign_str ;
    }

    /**
     * 把数组所有元素，除去空值,按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para  需要拼接的数组
     * @param int $is_urlencode  是否需要urlencode编码
     * @return string  拼接完成以后的字符串
     */
    public function createLinkstringUrlencode($para, $is_urlencode=1) {
        $arg  = "";
        foreach ($para as $key=>$val) {
            if($is_urlencode){
                $val = urlencode($val) ;
            }
            $arg.=$key."=".$val."&" ;
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $arg = stripslashes($arg);
        }
        return $arg;
    }
		/**
     * 获取员工信息
     * @param string $service_name,可不填，默认返回所有员工信息；有值时，返回员工名称等于该值的信息
     * @param int $page
     * @param int $page_no
     * @return mixed
     */
    public function getServiceInfo($service_name='',$page=1,$page_no=20){
        $request_type = "getserviceinfo" ;
        $param = $this->oauth->getSystemParam() ;
        $param["method"] = "youwuku.serviceinfo.get" ;
        $param['service_name'] = $service_name;
        $param['request_type'] = $request_type;
        $param["page"] = $page ;
        $param["page_no"] = $page_no ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        var_dump($result);exit;
        return json_decode($result) ;
    }
    /**
     * 获取订单信息
     * @param string $order_id   订单id 订单id为空返回当前商家的所有订单    可选字段
     * @param string $fields    请求查询的订单数据字段，多字段以英文 , 隔开    必要字段
     * @param null $order_status   请求查询的订单状态，订单状态为空表示请求状态为所有状态    可选字段
     * @param int $page    请求查询的页码    可选字段
     * @param int $page_no    页容量，每次返回页包含的数据条数    可选字段
     * @param string $start_time    订单修改起始时间，格式：2012-12-12 12:11:00    可选字段
     * @param string $end_time    订单修改结束时间，格式：2012-12-12 12:11:00    可选字段
     * @param string $buyer_id   买家id，  可选字段，默认为null
     * @return string
     */
    public function order_get($order_id, $fields, $order_status=NULL, $page=1, $page_no=20, $start_time="", $end_time="",$buyer_id=null){
        $request_type = "order" ;
        $param = $this->oauth->getSystemParam() ;
        $param["order_id"] = $order_id ;
        $param["fields"] = $fields ;
        $param["method"] = "youwuku.order.get" ;
        $param["request_type"] = $request_type ;
        $param["order_status"] = $order_status ;
        $param["page"] = $page ;
        $param["page_no"] = $page_no ;
        $param["start_time"] = $start_time ;
        $param["end_time"] = $end_time ;
        $param["buyer_id"] = $buyer_id ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 根据不同条件获取退款订单
     * @param $order_id
     * @param $refund_id
     * @param $page
     * @param $page_no
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function refundOrderGet($order_id,$refund_id,$page,$page_no,$start_time,$end_time,$fields)
    {
        $request_type = "order" ;
        $param = $this->oauth->getSystemParam() ;
        $param["order_id"] = $order_id ;
        $param["fields"] = $fields ;
        $param["refund_id"] = $refund_id ;
        $param["method"] = "youwuku.order.refund.get" ;
        $param["request_type"] = $request_type ;
        $param["page"] = $page ;
        $param["page_no"] = $page_no ;
        $param["start_time"] = $start_time ;
        $param["end_time"] = $end_time ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 修改订单状态
     * @param string $order_id    订单id    必要字段
     * @param string $order_status    请求修改的订单状态    必要字段
     * @return mixed
     */
    public function order_status_set($order_id, $order_status){
        $request_type = "order" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["order_id"] = $order_id ;
        $param["order_status"] = $order_status ;
        $param["method"] = "youwuku.order.status.set" ;
        $param["request_type"] = $request_type ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 物流发货接口
     * @param string $order_id    订单id    必要字段
     * @param string $invoice_no    物流发货单号    必要字段
     * @param string $logistics_name    物流公司名称    必要字段
     * @param string $transport_type    物流运输类型POST（平邮）、EXPRESS（快递）、EMS（EMS）    必要字段
     * @return string
     */
    public function logistics_send($order_id, $invoice_no, $logistics_name, $transport_type ){
        $request_type = "order" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["order_id"] = $order_id ;
        $param["method"] = "youwuku.logistics.send" ;
        $param["request_type"] = $request_type ;
        $param["invoice_no"] = $invoice_no ;
        $param["logistics_name"] = $logistics_name ;
        $param["transport_type"] = $transport_type ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 商品获取
     * @param $fields
     * @param int $page
     * @param int $page_no
     * @param string $id
     * @param string $outer_id
     * @param string $start_time
     * @param string $end_time
     * @return mixed
     */
    public function prd_get($fields, $page=1, $page_no=20,$id=null, $outer_id="", $start_time="", $end_time=""){
        $request_type = "prd" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["id"] = $id ;
        $param["fields"] = $fields ;
        $param["method"] = "youwuku.prd.get" ;
        $param["request_type"] = $request_type ;
        $param["outer_id"] = $outer_id ;
        $param["page"] = $page ;
        $param["page_no"] = $page_no ;
        $param["start_time"] = $start_time ;
        $param["end_time"] = $end_time ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 商品状态更新
     * @param $id
     * @param $approve_status
     * @return mixed
     */
    public function prd_set_approve_status($id,$approve_status){
        $request_type = "prd" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["id"] = $id ;
        $param["method"] = "youwuku.approvestatus.set" ;
        $param["request_type"] = $request_type ;
        $param["approvestatus"] = $approve_status ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 修改商品的价格
     * @param $id
     * @param $price
     * @return mixed
     */
    public function prd_change_price($id,$price){
        $request_type = "prd" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["id"] = $id ;
        $param["method"] = "youwuku.price.change" ;
        $param["request_type"] = $request_type ;
        $param["price"] = $price ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 修改商品库存
     * @param $id
     * @param $num
     * @param string $sku_id
     * @param string $outer_id
     * @return mixed
     */
    public function prd_change_num($id,$num,$sku_id="",$outer_id=""){
        $request_type = "prd" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["id"] = $id ;
        $param["method"] = "youwuku.prdnum.change" ;
        $param["request_type"] = $request_type ;
        $param["num"] = $num ;
        $param["skuid"] = $sku_id ;
        $param["outer_id"] = $outer_id ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 根据$client_id获取员工业绩
     * @param string $client_id  $client_id
     * @param string $start_day 开始日期
     * @param string $end_day   结束日期
     * @return mixed
     */
    public function service_formace_getall($sina_uid,$start_day=null,$end_day=null,$page_no=10,$page=1,$search_name){
        $request_type = "getserviceinfo" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["uid"] = $sina_uid ;
        $param["method"] = "youwuku.service.performance.getall" ;
        $param["request_type"] = $request_type ;
        $param["start_day"] = $start_day ;
        $param["end_day"] = $end_day ;
        $param["page_no"] =$page_no ;
        $param["page"] = $page ;
        $param["search_name"] = $search_name;
       
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        
        return $result;
    }
    /**
     * 根据员工id和$client_id获取员工，获取改员工的业绩
     * @param string $client_id  $client_id
     * @param string $id        员工id
     * @param string $start_day 开始日期
     * @param string $end_day   结束日期
     * @return mixed
     */
    public function service_formace_getinfo($sina_uid,$id,$start_day=null,$end_day=null,$page_no=10,$page=1){
        $request_type = "getserviceinfo" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["uid"] = $sina_uid ;
        $param["send_id"] = $id ;
        $param["method"] = "youwuku.service.performance.getinfo" ;
        $param["request_type"] = $request_type ;
        $param["start_day"] = $start_day ;
        $param["end_day"] = $end_day ;
        $param["page_no"] = $page_no ;
        $param["page"] = $page ;
        
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 根据员工id和商家uid获取员工，获取改员工的业绩
     * @param string $sina_uid  商家uid，必填字段
     * @param string $send_id        员工id,必填字段
     * @param int $operation      操作,1代表扫码关注，2代表取消关注，可选字段，默认为1
     * @param string $start_day 开始日期，可选字段，格式(2015-08-08)
     * @param string $end_day   结束日期,可选字段，格式(2015-08-08)
     * @param int $page_no      每页数据条数，可选字段，默认为10
     * @param int $page          当前页数，可选字段，默认为1
     * @return mixed
     */
    public function service_formace_getfans($uid,$send_id,$operation,$start_day=null,$end_day=null,$page_no=10,$page=1){
         
        $request_type = "getserviceinfo" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
         
        $param["uid"] = $uid ;
        $param["send_id"] = $send_id ;
        $param["operation"] = $operation ;
        $param["method"] = "youwuku.service.performance.getfans" ;
        $param["request_type"] = $request_type ;
        $param["start_day"] = $start_day ;
        $param["end_day"] = $end_day ;
        $param["page_no"] = $page_no ;
        $param["page"] = $page ;
    
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     *
     * @param unknown $uid  商家id 必填
     * @param unknown $account 账号（必填）
     * @param unknown $account_nick 账号昵称（必填）
     * @param unknown $password    账号密码（必填）
     * @return result
     */
    public function serviceadd($uid,$account,$account_nick,$password){
        $request_type = "getserviceinfo" ;
         
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["uid"] = $uid ;
        $param["method"] = "youwuku.service.performance.serviceadd" ;
        $param["request_type"] = $request_type ;
        $param["account"] = $account ;
        $param["account_nick"] = $account_nick ;
        $param["pass"] = $password;
    
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
    /**
     * 获取用户openid
     * @param $client_id
     * @param $redirect_url 授权后重定向的回调链接地址，请使用urlencode对链接进行处理
     * @param $authtype 用户授权方式，1：弹出授权页面，获取openid ,0：不弹出授权页面，直接跳转，获取用户openid
     * @param int $is_redirect 返回组装好的请求url
     * @return result
     */
    public function getUserOpenid($client_id,$authtype,$redirect_url,$is_redirect = 1){
        $request_type = "jump" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["sid"] = $client_id ;
        $param["authtype"] = $authtype ;
        $param["method"] = "youwuku.fans.openid" ;
        $param["redirect_url"] = $redirect_url ;
        $param["request_type"] = $request_type ;
        $result = $this->http($param,$this->oauth->host.$request_type,'POST',$is_redirect) ;
        return $result;
    }

    /**
     * 获取粉丝信息
     * @param $client_id
     * @param $openid
     * @return mixed|string
     */
    public function getFansInfo($client_id,$openid){
        $request_type = "getuserinfo" ;
        $param = $this->oauth->getSystemParam() ;  //系统级参数
        $param["sid"] = $client_id ;
        $param["openid"] = $openid ;
        $param["method"] = "youwuku.fans.info" ;
        $param["request_type"] = $request_type ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
	/**
     * 获取客服的聊天记录
     * @param unknown $sid          卖家id，不能为空
     * @param unknown $service_id   客服id，不能为空
     * @param unknown $page_no      每页数据条数，默认值是100,最大值是500 选填
     * @param unknown $page         当前页码，默认是1 选填
     * @param unknown $start_day    开始时间，默认是从昨天0:00:00 选填
     * @param unknown $end_day      结束时间，默认是昨天23:59:59 开始和结束时间不能超过7天 选填
     * **/
	public function chatinfo_get($sid,$service_id,$page_no,$page,$start_day,$end_day){
        $request_type = "getserviceinfo" ;
        $param = $this->oauth->getSystemParam() ;
        $param["method"] = "youwuku.service.chatinfo.get" ;
        $param["request_type"] = $request_type ;
        $param["service_id"] = $service_id ;
        $param["page"] = $page ;
        $param["page_no"] = $page_no ;
        $param["start_day"] = $start_day ;
        $param["end_day"] = $end_day ;
        $result = $this->http($param,$this->oauth->host.$request_type) ;
        return $result;
    }
}