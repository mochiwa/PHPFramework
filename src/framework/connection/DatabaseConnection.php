<?php

namespace Framework\Connection;

use PDO;

/**
 * Description of DatabaseConnection
 * 
 *
 * @author mochiwa
 */
class DatabaseConnection {
    const POSTGRES='pgsql';
    const LOCAL='localHost';
    /**
     * @var string the pdo type (postgres,oracle,...)
     */
    private $pdoType;
    /**
     * @var string username to connect
     */
    private $username;
    /**
     * @var password to connect user
     */
    private $password;
    /**
     *
     * @var string the database name
     */
    private $dbName;
    /**
     *
     * @var string the hostAddress 
     */
    private $host;
    /**
     * @var int port 
     */
    private $port;
    /**
     *
     * @var PDO 
     */
    private $connection;
    
    public function __construct(string $pdoType,string  $username,string  $password ,string  $dbName,string  $host,int $port) {
        $this->pdoType = $pdoType;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
        $this->host = $host;
        $this->port = $port;
        if($port===0 && $host=self::LOCAL){
            $this->connection=new PDO($this->pdoType.':user='.$this->username.';password='.$this->password.';dbname='.$this->dbName);
        }else{
            $this->connection=new PDO($pdoType.':host='.$host.';port='.$port.';dbname='.$dbName, $username, $password);
        }
    }
    
    /**
     * Make a connection with postgres PDO,
     * If you use apache from docker repository don't forget to install the pdo with docker-php-ext-install pdo_pgsql
     * @param string $username
     * @param string $password
     * @param string $dbName
     * @param string $host
     * @param int $port
     * @return \self
     */
    public static function postgres(string  $username,string  $password ,string  $dbName,string  $host,int $port):self{
        return new self(self::POSTGRES,$username,$password,$dbName,$host,$port); 
    }
    
    /**
     * Create a location connection for postgres database
     * @param string $username
     * @param string $password
     * @param string $dbName
     * @return \self
     */
    public static function postgresLocal(string  $username,string  $password ,string  $dbName):self{
        return  new self(self::POSTGRES,$username,$password,$dbName,self::LOCAL,0); 
    }
    
    public function connection():PDO{
        return $this->connection;
    }

}
