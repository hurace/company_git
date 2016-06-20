<?php 
/**
 * 从缓存中取seller_id open_id 
 * 通过seller_id open_id 在mongodb中获取用户的等级、购物金额、购买次数、积分
 * 根据购物金额、购买次数、积分算出新等级 
 * 和旧等级比较 有变化 在mongodb中更新相应的等级 
 * **/
error_reporting(-1);

require_once '/data/www/dianking/egouhxb/shop/env.php';
require_once '/data/www/dianking/egouhxb/shop/class/LevelAndLabel.php';
//seller_id w132
//open_id oNKgCj9hNxnAxNIsCe-Y9n0gy4E4

//从redis中获取seller_id open_id
class byRedisGetSidOid
{
    public function getSidOid(){
        $redis = new RedisClient();
        
        
        $key = "sidoid";//
        $value = array('seller_id'=>'w132','open_id'=>'oNKgCj9hNxnAxNIsCe-Y9n0gy4E4');//
        $value = json_encode($value);//
        $a = $redis->setSadd($key, $value);//测试用
        
        
        
        $arr = $redis->setspop($key);
        if(!$arr){
            $log = "---getSidOid---redis中没有相关数据".date("Y-m-d H:i:s",time());
            file_put_contents("/data/www/dianking/egoushop/log/ChangeFansLevel.log", $log ."\n", FILE_APPEND); 
            exit;
        }
        $arr = json_decode($arr,true);
        // $b = $redis->getSmembers($key);
        //print_r($arr);
        return $arr;    
    }
}

class bySidOidGetUserInfo
{
    /**
     * 获取用户的 等级、购物金额、历史总积分、订单完成数
     * **/
    public function getUserInfo(){
        $getsidoid = new byRedisGetSidOid();
        $arr = $getsidoid->getSidOid();
        $seller_id = $arr['seller_id'];
        $open_id = $arr['open_id'];
        $mon=new M();
        $mongo_db=PublicFun::getConfigValue('Mongodb', 'label_db');//seller_fans    
        $mongo_collection=PublicFun::getConfigValue('Mongodb', 'label_fans_collection');//fans_info
        $fields = array('order_fee_finish','order_num_finish','historyCredit','new_level');
        $param = array(array('seller_id','==',$seller_id),array('open_id','==',$open_id));
        try{
            $data = $mon->getSellerFansInfo_Or_fields($mongo_db, $mongo_collection, $param, $fields);
            if($data){
                $data = $data[0];
                $data['new_level'] = $data['new_level']['key'];
                return $data;
            }else{
                $log = "---getUserInfo---error:seller_id=".$seller_id."---open_id=".$open_id."--mongo中没有数据". date("Y-m-d H:i:s", time());
                file_put_contents("/data/www/dianking/egoushop/log/ChangeFansLevel.log", $log . "\n", FILE_APPEND);
                return false;
            }
        }catch (Exception $e){
            $log = "---getUserInfo---error:" . $e->getMessage() . "---" . date("Y-m-d H:i:s", time());
            file_put_contents("/data/www/dianking/egoushop/log/ChangeFansLevel.log", $log . "\n", FILE_APPEND);
            return false;
        }
        
        //[order_fee_finish] => 0.07  购物金额
        //[order_num_finish] => 5 订单完成数
        //[historyCredit] => 1001 //历史总积分
        //[new_level] => AA
      
    }  
    
    //获取商家的等级配置
    public function getSellerLevelConfig(){
        $getsidoid = new byRedisGetSidOid();
        $arr = $getsidoid->getSidOid();
        $seller_id = $arr['seller_id'];
        $mon=new M();
        $mongo_db=PublicFun::getConfigValue('Mongodb', 'label_db');//seller_fans
        $mongo_collection=PublicFun::getConfigValue('Mongodb', 'label_seller_collection');//seller_label
        $mongo_collection='seller_label';
        $fields = array('new_level_key');
        $param = array(array('seller_id','==',$seller_id));
        try{
            $data = $mon->getSellerFansInfo_Or_fields($mongo_db, $mongo_collection, $param, $fields);
            $data = json_decode($data[0]['new_level_key'],true);
            //print_r($data['data']);
            return $data['data'];
        }catch (ErrorException $e){
            $log = "---getSellerLevelConfig---error:" . $e->getMessage() . "---" . date("Y-m-d H:i:s", time());
            file_put_contents("/data/www/dianking/egoushop/log/ChangeFansLevel.log", $log . "\n", FILE_APPEND);
            return false;
        }
    }
    
    
    /**
     * 该函数的功能是把如 A AA AB AE等转换成相对应的等级 如 0 1 2 5 
     * @param array $config 商家的等级配置 
     * @param string $level fans的等级标志 如 A AA AB
     * **/
    public function byConfigGetLeval($config,$level){
        $test = new bySidOidGetUserInfo();
        $config = $test->getSellerLevelConfig();
        $level = "AE";
        if(array_key_exists($level, $config)){
            echo $config[$level]['level'];
            //print_r($config);
        }else{
            $log = "---byConfigGetLeval---level=".$level."--该等级在商家等级配置中不存在".date("Y-m-d H:i:s",time());
            file_put_contents("/data/www/dianking/egoushop/log/ChangeFansLevel.log", $log . "\n", FILE_APPEND);
        }
    }
    
    /**
     * 根据用户的购物金额、历史总积分、订单完成数
     * 获取用户对应的等级
     * @param userconfig 用户的购物金额、历史总积分、订单完成数信息
     * @return newLevel 变更后的等级
     * **/
    public function getFansLevel($userconfig){
        $test = new bySidOidGetUserInfo();
        $userconfig = $test->getUserInfo();
        $Credit = $userconfig['historyCredit'];
        $OrderFee = $userconfig['order_fee_finish'];
        $OrderNum = $userconfig['order_num_finish'];
        $LevelConfig = $test->getSellerLevelConfig();
        //遍历进行查看已设置自动打标签的标签顺序
        foreach ($LevelConfig as $tmpKey=>$single){
            if(is_numeric($single['level'])){
                $tmp[$tmpKey] =$single['level'];
            }
        
        }

        if(is_array($tmp)){
            arsort($tmp);
            foreach ($tmp as $sortKey=>$sortVal){
                	
                if($LevelConfig[$sortKey]['auto_add']['order_fee'] || $LevelConfig[$sortKey]['auto_add']['order_num'] || $LevelConfig[$sortKey]['auto_add']['credit_num']){
                    $tmpFee=floatval($LevelConfig[$sortKey]['auto_add']['order_fee']);
                    $tmpNum=floatval($LevelConfig[$sortKey]['auto_add']['order_num']);
                    $tmpCredit=floatval($LevelConfig[$sortKey]['auto_add']['credit_num']);
                    if($Credit>=$tmpCredit && $tmpCredit>0){
                        $newLevelKey=$sortKey;
                        break;
                    }
                    if($OrderFee>=$tmpFee && $tmpFee>0){
                        $newLevelKey=$sortKey;
                        break;
                    }
                    if($OrderNum>=$tmpNum && $tmpNum>0){
                        $newLevelKey=$sortKey;
                        break;
                    }
                }
            }            
    }
    echo $newLevelKey;
  }

  /**
   * 更新用户信息 主要是等级信息
   * **/
  public function updFansInfo($param,$set){
      $mon = new M();
      $mongo_db=PublicFun::getConfigValue('Mongodb', 'label_db');//seller_fans
      $mongo_collection=PublicFun::getConfigValue('Mongodb', 'label_fans_collection');//fans_info
      $res = $mon->UpdateWordCommon($mongo_db, $mongo_collection, $param, $set);
      if($res){
          
      }else{
          
      }
  }
  
}


//脚本执行函数
function scriptExecFun(){
    
}







?>