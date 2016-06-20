<?php

$default = array(  
    'unix_socket' => null,  
    'host' => 'localhost',  
    'port' => '3306',  
    'user' => 'root',  
    'password' => '',  
);  
    
$config = array(  
    // 不进行分库分表的数据库  
    'db' => array(  
        'my_site' => $default,  
    ),  
    // 分库分表  
    'shared' => array(  
        'user' => array(  
            'host' => array(  
                /** 
                 * 编号为 0 到 10 的库使用的链接配置 
                 */ 
                '0-10' => $default,  
                /** 
                 * 编号为 11 到 28 的库使用的链接配置 
                 */ 
                '11-28' => $default,  
                /** 
                 * 编号为 29 到 99 的库使用的链接配置 
                 */ 
                '29-99' => $default,  
    
            ),  
    
            // 分库分表规则  
            /** 
             * 下面的配置对应百库百表 
             * 如果根据 uid 进行分表，假设 uid 为 543234678，对应的库表为： 
             *  (543234678 / 1) % 100 = 78 为编号为 78 的库 
             *  (543234678 / 100) % 100 = 46 为编号为 46 的表 
             */ 
            'database_split' => array(1, 100),  
            'table_split' => array(100, 100),  
        ),  
    ),  
);  
    
    
return $config;


?>