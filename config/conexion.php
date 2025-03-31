<?php
// Incluimos el archivo de configuración

class Conexion
{
    private $server_name;
    private $user_name;
    private $user_password;
    private $db_name;
    public $dsn;
    public $option;
    public function __construct($server_name, $user_name, $user_password, $db_name)
    {
        $this->server_name = $server_name;
        $this->user_name = $user_name;
        $this->user_password = $user_password;
        $this->db_name = $db_name;
        $this->dsn = "mysql:host=" . $server_name . ";dbname=" . $db_name;
        $this->option = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    //metodo para modificar los datos de la configuracion de la base de datos
    public function setConexion($server, $user, $password, $db){
        $this->server_name = $server;
        $this->user_name = $user;
        $this->user_password = $password;
        $this->db_name = $db;
    }

    public function  getConexion(){
        try { 
            $conexion = new PDO($this->dsn, $this->user_name, $this->user_password, $this->option);
           // echo "conexion exitosa";
        } catch (PDOException $e) {
            echo "" . $e->getMessage();
        }
       return $conexion;
    } 
    public function closeConexion(){
        return $this->conexion = null;
    }
}


//creando el objeto de conexion 
$pdo = new Conexion("localhost","root","","examenes_online");



?>