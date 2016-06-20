<?php

    /**
     * 定义接口
     * */
    interface IDB {
        public function connect($host,$user,$pwd,$db,$charset='utf8');
        public function getUserInfo($id);
        public function postNewFeed($feed);
    }
    
    /**
     * mysql实现
     * */
    class MySql implements IDB{
        public function connect($host,$user,$pwd,$db,$charset='utf8'){}
        public function getUserInfo($id){}
        public function postNewFeed($feed){}
    }
    
    /**
     * plsql实现
     * */
    class PLSQL implements IDB{
        public function connect($host,$user,$pwd,$db,$charset='utf8'){}
        public function getUserInfo($id){}
        public function postNewFeed($feed){}
    }
    
    /**
     * 工厂方法
     * */
    class DBFactory{
        private static $db = array();
        
        public function getInstance($type){
            switch(strtolower($type)){
                case 'mysql':
                    if(!isset(self::$db['mysql'])){
                        self::$db['mysql'] = new MySql();
                    }
                case 'plsql':
                    if(!isset(self::$db['plsql'])){
                        self::$db['plsql'] = new PLSQL();
                    }
                default:
//                     throw new Exception('You must assign the db type!');
            }
        }
    }

    $db = DBFactory::getInstance('mysql');
    var_dump($db);exit;
    $user_info = $db->getUserInfo($id);

?>