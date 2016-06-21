<?php
require_once "BaseController.php";
require_once APPLICATION_PATH.'/models/Item.php';
class AdminController extends BaseController
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $this->render('index');
    }
    
    //进入到增加选项的页面
    public function additemuiAction(){
        //$this->render('additemui');   
    }
    
    //执行添加动作
    public function additemAction(){
        //获取用户输入的内容
        $name = $this->getRequest()->getParam('name');
        $descr = $this->getRequest()->getParam('descrption');
        $vote_count = $this->getRequest()->getParam('vote_count');
        
        $data = array(
            'id' => '',
            'name' => $name,
            'descrption' => $descr,
            'vote_count' => $vote_count
        );
       
        //创建一个表模型对象
       $itemModel=new Item();
       $itemModel->insert($data);
        $this->render('ok');
    }

}

