<?php 

$fields = array(
	'sum(abc) as abc','sum(dc) as dc','sum(fg) as fg'
);



                        if($nick){
                            if(preg_match("/13[123569]{1}\\d{8}|15[1235689]\\d{8}|188\\d{8}/",$tel)){
                                $exist = $getinfo->checkExist($sid, $buyer_id, $tel, $plat_type, $nick);
                                if($exist == 0){
                                    $insresult = $getinfo->insInfo($sid, $buyer_id, $tel, $plat_type, $nick);
                                    if($insresult){
                                        $list = array('errcode'=>0,'errmsg'=>'success');
                                    }else{
                                        $list = array('errcode'=>1,'errmsg'=>'系统繁忙');
                                    }
                                }else{
                                    $list = array('errcode'=>0,'errmsg'=>'success');
                                }
                            }else{
                                $list = array('errcode'=>1,'errmsg'=>'手机号码格式不对,请核对后再输入');
                            }
                        }else{
                            $list = array('errcode'=>1,'errmsg'=>'用户名为空');
                        }
                    }



echo implode(',',$fields);
file_put_contents('./log.txt',$fields,FILE_APPEND);

?>

 