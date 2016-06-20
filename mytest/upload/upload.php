<?php
 var_export($_FILES);
 $excel_calss = new ExcelClass();
 $result=$excel_calss->upExcel($_FILES);
 class ExcelClass{
	 /**
     * 处理excel上传 
     * @param unknown $file excel文件信息  
     */
    public function  upExcel($file){
            if(!$file){
                return array('code'=>1,'msg'=>'上传文件不能为空，请重新选择');
            }
            $tempFile = $file['Filedata']['tmp_name'];
            $fileName = $file['Filedata']['name'] ;  //图片名称
            $fileInfo = explode(".",$fileName) ;
            $last_pos = strrpos( $fileName, "." );  // 3
            $pic_name = substr( $fileName, 0, $last_pos ); // 123
            $lastFlag = $fileInfo[count($fileInfo)-1] ;
            $file_name = md5_file($tempFile);
            
			$tmp_dir = 'F:/wamp/www/mytest/upload/';
			$fina_name = $file_name.'.'.$lastFlag;
            $file_root = $tmp_dir.'/'.$fina_name;
            $result = move_uploaded_file($tempFile, $file_root);
			var_export($result);
			exit;
         
            if(!is_dir($tmp_dir)){
                mkdir($tmp_dir, 0777, true);
            }
            
            if(file_exists($file_root)){
                return array('code'=>1,'msg'=>'文件已经存在 ,请勿重复上传 ');
            }
   
            if(!$result){
                return array('code'=>1,'msg'=>'文件已经存在 ,请勿重复上传 ');
            }
            $target_file=$tmp_dir.'/'.$fina_name;
            $excel_clss=new ExcelClass();
            //$excel_result=$excel_clss->getExcel($lastFlag, $target_file);
            $excel_result=$excel_clss->getCsvContent($lastFlag, $target_file);
            $highestRow = $excel_result['row'];  //取得总行数
            $highestColumn = $excel_result['column'];
            $max_num=PublicFun::getConfigValue('excel_space','max_num');
            if(!$highestRow ){
                unlink($file_root);
                return array('code'=>1,'msg'=>'文件内容不能为空');
            }else if($highestRow>$max_num){
                unlink($file_root);
               return array('code'=>1,'msg'=>'excel文件 数据不能超过10万条');
            }else{
                $check_code=$this->detect_encoding($file_root);
                if(!$check_code){
                    unlink($file_root);
                    return array('code'=>1,'msg'=>'文件编码不正确');
                }
            }
            if($result){
                return array('code'=>0,'msg'=>$fina_name,'dir'=>$file_root,'file_name'=>$fileName);
            }
    }
 }
?>