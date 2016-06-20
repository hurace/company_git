<?php

class single{
	private static $ins = null;
	// 构造函数和克隆函数必须声明为私有的，防止外部程序new类从而失去单例模式的意义
	private function __construct(){
		echo 111;
	}
	//覆盖__clone()方法，禁止克隆  
	private function __clone(){

	}
	// 必须提供一个访问这个实例的公共的静态方法（通常为getInstance方法），从而返回唯一实例的一个引用
	public static function getInstance(){
		if(self::$ins instanceof self){
			self::$ins = new self();
		}
		return self::$ins;
	}
}

$s1 = single::getInstance();
$s2 = single::getInstance();
if($s1===$s2){
	echo "是同一个对象";
}

?>