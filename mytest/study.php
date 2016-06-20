<?php

//realpath()返回规范化的绝对路径名 string realpath  ( string $path  )
//__FILE__ //F:\wamp\www\mytest\study.php
//echo dirname(__FILE__);F:\wamp\www\mytest
//echo __DIR__;F:\wamp\www\mytest

//echo realpath("F:/wamp/www"."/./");//F:\wamp\www
//echo realpath("F:/wamp/www"."/../");//F:\wamp

//echo getcwd();//getcwd() - 取得当前工作目录F:\wamp\www\mytest
/*
class  Student{
	public $name;
	protected $roll;
	private $age;
	static $class;

	public function __construct(){
		$this->name = 'hurace';
		$this->roll = 'hua';
		$this->age  = 26;
		self::$class = '10 + 2';
	}
}

$stu = new Student();

$ser = serialize($stu);

file_put_contents('./serialize.txt', $ser);
//echo $ser;//O:7:"Student":3:{s:4:"name";s:6:"hurace";s:7:"*roll";s:3:"hua";s:12:"Studentage";i:26;}
$con = file_get_contents('serialize.txt');
$unser = unserialize($con);
//print_r($unser);//Student Object ( [name] => hurace [roll:protected] => hua [age:Student:private] => 26 ) 

*/
//chdir() 函数把当前的目录改变为指定的目录。若成功，则该函数返回 true，否则返回 false。
//echo getcwd();F:\wamp\www\mytest
//echo chdir('pdo');1
//echo getcwd();F:\wamp\www\mytest\pdo

?>