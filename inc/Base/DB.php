<?php
namespace Inc\Base;


class DB{


    /** @var string LOGS LOCAL  */
    private $dbhost = '127.0.0.1';
    private $dbusername = 'root';
    private $dbpassword = '';
    private $dbname = 'u643107709_apocalypstar';


    /** @var string LOG DB SERVEUR */
    private $dbhost_prod = 'localhost';
    private $dbusername_prod = 'root';
    private $dbpassword_prod = '';
    private $dbname_prod = 'u643107709_apocalypstar';

    protected $pdo;


    public function __construct($host = NULL, $username = NULL, $password = NULL, $dbname = NULL)
    {
        if ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == 'localhost'){
            try
            {
                $this->pdo = new \PDO('mysql:host='. $this->dbhost. ';dbname='.$this->dbname, $this->dbusername, $this->dbpassword, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            }
            catch (\PDOException $e)
            {
                die('Une erreur est survenue pendant la connexion a la Base de Donnee');
            }
        }

        else {
            try
            {
                $this->pdo = new \PDO('mysql:host='. $this->dbhost_prod. ';dbname='.$this->dbname_prod, $this->dbusername_prod, $this->dbpassword_prod, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            }
            catch (\PDOException $e)
            {
                die('Une erreur est survenue pendant la connexion a la Base de Donnee');
            }
        }


    }
}
