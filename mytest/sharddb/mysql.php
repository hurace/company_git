<?php

$default = array(  
    'unix_socket' => null,  
    'host' => 'localhost',  
    'port' => '3306',  
    'user' => 'root',  
    'password' => '',  
);  
    
$config = array(  
    // �����зֿ�ֱ�����ݿ�  
    'db' => array(  
        'my_site' => $default,  
    ),  
    // �ֿ�ֱ�  
    'shared' => array(  
        'user' => array(  
            'host' => array(  
                /** 
                 * ���Ϊ 0 �� 10 �Ŀ�ʹ�õ��������� 
                 */ 
                '0-10' => $default,  
                /** 
                 * ���Ϊ 11 �� 28 �Ŀ�ʹ�õ��������� 
                 */ 
                '11-28' => $default,  
                /** 
                 * ���Ϊ 29 �� 99 �Ŀ�ʹ�õ��������� 
                 */ 
                '29-99' => $default,  
    
            ),  
    
            // �ֿ�ֱ����  
            /** 
             * ��������ö�Ӧ�ٿ�ٱ� 
             * ������� uid ���зֱ����� uid Ϊ 543234678����Ӧ�Ŀ��Ϊ�� 
             *  (543234678 / 1) % 100 = 78 Ϊ���Ϊ 78 �Ŀ� 
             *  (543234678 / 100) % 100 = 46 Ϊ���Ϊ 46 �ı� 
             */ 
            'database_split' => array(1, 100),  
            'table_split' => array(100, 100),  
        ),  
    ),  
);  
    
    
return $config;


?>