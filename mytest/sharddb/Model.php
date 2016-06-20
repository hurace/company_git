<?php
    
abstract class Model  
{  
    /** 
     * @var Config 
     */ 
    public $config;  
    
    /** 
     * @var PDO 
     */ 
    public $connection;  
    
    protected $dbnamePrefix;  
    protected $tablePrefix;  
    
    /** 
     * @var string 分库分表后对应的表 
     */ 
    protected $table;  
    
    public function __construct($id)  
    {  
        $this->config = new Config($this->dbnamePrefix, $this->tablePrefix, $id);  
        // $this->connection = new Pdo($this->config->dsn, $this->config->user, $this->config->password);  
        $this->table = $this->config->table;  
    }  
    
    public function update(array $data, array $where = array())  
    {  
    
    }  
    
    public function select(array $where)  
    {  
    
    }  
    
    public function insert(array $data)  
    {  
    
    }  
    
    public function query($sql)  
    {  
        return $this->connection->query($sql);  
    }  
}

?>