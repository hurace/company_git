<?php  

$soapUrl = "http://smscent.centaur.cn/jkSoap/soap?wsdl";//soap";接口地址
$user_name = "edba74072";//用户账户
$vcode = md5(md5("youwuku@321"));//客户效验码
$client = new SoapClient($soapUrl,array('trace' => false));//实例化soap接口
$content = "亲!" ; //要发送的短信内容
$sign = "hurace"; //店铺签名
$mobiles = "15010876245,18518751618"; //要发送的手机号
$info_ids = "hurace"; //信息来源
//$orderTime = "2013-07-01 12:23:45"; //发送的时间，如果为空，则是立即发送
$operateId = "XXXXXXX"; //自动产生的批次
//$client = new SoapClient($soapUrl,array('trace' => false)); //实例化soap接口
$a = $client->SmsSend($user_name, $vcode, $content, $sign, $mobiles, $info_ids, '', $operateId); //调用发送的接口并接受返回值
echo ('<pre>');
print_r(explode(":",$a));
var_dump($a);
//var_dump ( $client->__getFunctions () );//获取服务器上提供的方法
echo ('</pre>');
//echo $client->httpurl;
//var_dump($client);echo ("SOAP服务器提供的Type:");


?>