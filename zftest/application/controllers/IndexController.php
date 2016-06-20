<?php
require_once APPLICATION_PATH.'/models/User.php';

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $userModel = new User();
       $user = $userModel->fetchAll()->toArray();
       $this->view->user = $user;
       $this->render('index');
    }


}

