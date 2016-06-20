<?php 

/**
 * 
 * */
interface IMobileStorage{
    public function read();//
    public function write();
}

class FlashDisk implements IMobileStorage{
    public function read(){
        echo 'Reading from FlashDish!<br/>';
    }    
    public function write(){
        echo 'Writing to FlashDisk!<br/>';
    }
}

class MP3Player implements IMobileStorage{
    public function read(){
        echo 'Reading from MP3Player!<br/>';
    }
    public function write(){
        echo 'Writing to MP3Player!<br/>';
    }
}

class MobileHardDish implements IMobileStorage{
    public function read(){
        echo 'Reading from MobileHardDisk!<br/>';
    }
    public function write(){
        echo 'Writing to MobileHardDisk!<br/>';
    }
}

class Computer{
    public function read(IMobileStorage $type){        
        $type->read();
    }
    public function write(IMobileStorage $type){
        $type->write();
    }
}

$computer = new Computer();
$flashdisk = new FlashDisk();
$mp3 = new MP3Player();
$computer->read($flashdisk);
$computer->read($mp3);
$computer->write($flashdisk);
$computer->write($mp3);
?>