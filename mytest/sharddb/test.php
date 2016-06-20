<?php

require 'Config.php';  
require 'Model.php';  
    
class User extends Model  
{  
    protected $dbnamePrefix = 'user';  
    protected $tablePrefix = 'userinfo';  
}  
    
$user = new User(4455345345);  
    
print_r($user);


?>