<?php 

/**
    * �����ύ
    * @param string $user_name   #�û��˺�                                                       
    * @param string $vcode       #�û�������: md5(md5('123456'))     
    * @param string $content     #�������� ����<=60��Ӣ��+���� 
    * @param string $sign        #����ǩ��                                         
    * @param string $mobiles     #�ֻ����� ����� , �ָ������5000����                                     
    * @param string $info_ids    #��Ϣ��Դ���Զ����ӣ�jk_ ����ͳ�ƣ�
    * @param string $orderTime    #��ʱʱ��
    * @param string $operateId    #�ͻ������κţ�jk-xxx(������jk-��ͷ)
    * @return string       #���ظ�ʽ�� ok:�ɹ�����,ʧ������,���֣�,�۸񣨷֣���fault:ʧ��ԭ��                   
    */
function SmsSend(string $user_name, string $vcode, string $content, string $sign, string $mobiles, string $info_ids, string $orderTime, string $operateId);

 /**
    * ��ѯ���͵���
    * @param string $user_name   #�û��˺�                                                       
    * @param string $vcode       #�û�������: md5(md5('123456'))     
    * @return string       #���ظ�ʽ�� ok:���֣�,�۸񣨷֣���fault:ʧ��ԭ��                   
    */
	
function getBalance(string $user_name, string $vcode);


  /**
    * ��ȡ����ͳ����Ϣ
    * @param string $user_name   #�û��˺�                                                       
    * @param string $vcode       #�û�������: md5(md5('123456'))
    * @param string $batch_no    #���κ�     
    * @return string       	 	 #���ظ�ʽ�� ok:�ֻ�����,��������,�ܷ���,��ִ�ɹ�����,��ִʧ������/fault:ʧ��ԭ��                   
    */
function getBatchReport(string $user_name, string $vcode, string $batch_no);	



?>