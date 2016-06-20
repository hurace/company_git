<?php 

/**
    * 短信提交
    * @param string $user_name   #用户账号                                                       
    * @param string $vcode       #用户名密码: md5(md5('123456'))     
    * @param string $content     #短信内容 长度<=60个英文+汉字 
    * @param string $sign        #短信签名                                         
    * @param string $mobiles     #手机号码 多个以 , 分隔。最大5000个。                                     
    * @param string $info_ids    #信息来源（自动增加：jk_ 用于统计）
    * @param string $orderTime    #定时时间
    * @param string $operateId    #客户端批次号：jk-xxx(必须以jk-开头)
    * @return string       #返回格式： ok:成功条数,失败条数,余额（分）,价格（分）；fault:失败原因                   
    */
function SmsSend(string $user_name, string $vcode, string $content, string $sign, string $mobiles, string $info_ids, string $orderTime, string $operateId);

 /**
    * 查询余额和单价
    * @param string $user_name   #用户账号                                                       
    * @param string $vcode       #用户名密码: md5(md5('123456'))     
    * @return string       #返回格式： ok:余额（分）,价格（分）；fault:失败原因                   
    */
	
function getBalance(string $user_name, string $vcode);


  /**
    * 获取批次统计信息
    * @param string $user_name   #用户账号                                                       
    * @param string $vcode       #用户名密码: md5(md5('123456'))
    * @param string $batch_no    #批次号     
    * @return string       	 	 #返回格式： ok:手机数量,短信数量,总费用,回执成功数量,回执失败数量/fault:失败原因                   
    */
function getBatchReport(string $user_name, string $vcode, string $batch_no);	



?>