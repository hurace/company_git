<?php
//作者：遥远的期待
//QQ:15624575
//主页：http://www.phptogether.com/
//正向排序的数组
$arr=array(1,3,5,7,9,11,8,8);
//逆向排序的数组
$arr2=array(11,9,7,5,3,1);
//对正向排序的数组进行二分查找
function searchpart($arr,$x){
	$start=0;
	$end=count($arr)-1;
	
	while($start<=$end){
		$mid=ceil(($start+$end)/2);//这里只需要保证中间项下标的计算值为整数即可，也可以四舍五入，不影响结果
		if($arr[$mid]>$x){	//如果中间项的值大于待查值，说明代差值位于中间项的左边，因此，起始下标不变，结束下标变成中间项下标减1，第一次搜索的是$arr[0]-$arr[5]的话，下一次搜索
		$end=$mid-1;//$arr[0]-$arr[1]
		echo $end."end";
		}else if($arr[$mid]<$x){//如果中间项的值小于待查值，说明代差值位于中间项的右边，因此，结束下标不变，起始下标变成中间项下标加1，第一次搜索的是$arr[0]-$arr[5]的话，下一//次搜索是，$arr[3]-$arr[5]
		$start=$mid+1;
		echo $start."start";
		}else{//找到了，返回待查值下标
		// echo $start;
		echo $mid."mid";
		// echo $end;
		exit;
		}
	}
} 

//searchpart($arr,10);

sort($arr);
print_r($arr);







