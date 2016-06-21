<?php
//这里必须继承Zend_Db_Table，否则就不是表模型
class  Item extends  Zend_Db_Table{
	//默认主键为id  可以不写
    protected   $_primary='id';
	protected  $_name='item';
}