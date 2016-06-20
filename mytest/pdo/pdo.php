<?php 
$dsn = "mysql:dbname=mytest;host=localhost";
try{
	$pdo = new PDO($dsn,'root','');
	//设置错误处理模式为异常模式
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo $e->getMessage();
}
try{
	//准备好了一条语句，并发送到服务器端，也已经编译过来了，就差为它分配数据了
	$stmt = $pdo->prepare('insert into user(id,username) values(:id,:username)');
	//绑定参数
	$stmt->bindParam(":id",$id);
	$stmt->bindParam(":username",$name);

	$id = 5;
	$name = 'hurace';

	if($stmt->execute()){
		echo "执行成功";
	}else{
		echo "shibai";
	}
	
}catch(PDOException $e){
	echo $e->getMessage();
}

/**
*pdo中有两种占位符
*？参数   ----索引数组，按索引顺序使用
*名字参数  -----关联数组，按名称使用，和顺序无关
*有结果集的query() 执行select语句
*exec() 用来执行有影响行数的 update delete insert other
*exec() 返回的是影响的行数
**/


?>
