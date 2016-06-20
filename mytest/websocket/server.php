<?php
include 'websocket.class.php';
 
$config=array(
  'address'=>'192.168.0.211',
  'port'=>'4000',
  'event'=>'WSevent',//�ص������ĺ�����
  'log'=>true,
);
$websocket = new websocket($config);
$websocket->run();
function WSevent($type,$event){
  global $websocket;
    if('in'==$type){
      $websocket->log('�ͻ�����id:'.$event['k']);
    }elseif('out'==$type){
      $websocket->log('�ͻ��˳�id:'.$event['k']);
    }elseif('msg'==$type){
      $websocket->log($event['k'].'��Ϣ:'.$event['msg']);
      roboot($event['sign'],$event['msg']);
    }
}
 
function roboot($sign,$t){
  global $websocket;
  switch ($t)
  {
  case 'hello':
    $show='hello,GIt @ OSC';
    break;  
  case 'name':
    $show='Robot';
    break;
  case 'time':
    $show='��ǰʱ��:'.date('Y-m-d H:i:s');
    break;
  case '�ټ�':
    $show='( ^_^ )/~~�ݰ�';
    $websocket->write($sign,'Robot:'.$show);
    $websocket->close($sign);
    return;
    break;
  case '�����ǵػ�':
    $array = array('С����Ģ��','���������','����������');
    $show = $array[rand(0,2)];
    break;
  default:
    $show='( ��o��?)����,����Գ���˵:hello,name,time,�ټ�,�����ǵػ�.';
  }
  $websocket->write($sign,'Robot:'.$show);
}
?>