<?php
// Clase para gestionar la conexión a la base de datos usando PDO
class Conexion
{
    // Propiedades privadas para los parámetros de conexión
    private $server_name;       // Nombre del servidor (ej. localhost)
    private $user_name;         // Nombre de usuario de la base de datos
    private $user_password;     // Contraseña del usuario de la base de datos
    private $db_name;           // Nombre de la base de datos

    // Propiedades públicas para DSN y opciones PDO
    public $dsn;                // Cadena de conexión DSN para PDO
    public $option;             // Opciones para configurar el comportamiento de PDO
    private $conexion;          // Instancia de la conexión PDO

    // Constructor de la clase - Inicializa los parámetros y configura DSN y opciones PDO
    public function __construct($server_name, $user_name, $user_password, $db_name)
    {
        // Asignación de parámetros de conexión
        $this->server_name = $server_name;
        $this->user_name = $user_name;
        $this->user_password = $user_password;
        $this->db_name = $db_name;

        // Creación de la cadena DSN (Data Source Name)
        $this->dsn = "mysql:host=" . $server_name . ";dbname=" . $db_name . ";charset=utf8mb4";

        // Opciones de configuración para PDO
        $this->option = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Lanza excepciones en caso de error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Devuelve los resultados como arrays asociativos
            PDO::ATTR_EMULATE_PREPARES => false                 // Desactiva la emulación de sentencias preparadas (más seguro)
        ];
    }

    // Método para actualizar los datos de conexión (opcional si se desea modificar luego)
    public function setConexion($server, $user, $password, $db)
    {
        $this->server_name = $server;
        $this->user_name = $user;
        $this->user_password = $password;
        $this->db_name = $db;
    }

    // Método para obtener una nueva conexión PDO
    public function getConexion()
    {
        try {
            // Se crea una nueva instancia PDO con los parámetros y opciones definidos
            $this->conexion = new PDO($this->dsn, $this->user_name, $this->user_password, $this->option);
        } catch (PDOException $e) {
            // Si ocurre un error, se muestra el mensaje de error
            echo "Error de conexión: " . $e->getMessage();
        }

        // Devuelve la instancia de la conexión PDO
        return $this->conexion;
    }

    // Método para cerrar la conexión asignando null al objeto PDO
    public function closeConexion()
    {
        return $this->conexion = null;
    }

    // Método para iniciar una transacción (BEGIN)
    public function beginTransaction()
    {
        if ($this->conexion) {
            $this->conexion->beginTransaction();
        }
    }

    // Método para confirmar una transacción (COMMIT)
    public function commit()
    {
        if ($this->conexion) {
            $this->conexion->commit();
        }
    }

    // Método para revertir una transacción (ROLLBACK)
    public function rollBack()
    {
        if ($this->conexion) {
            $this->conexion->rollBack();
        }
    }

    // Método para obtener el último ID insertado en la base de datos
    public function lastInsertId()
    {
        if ($this->conexion) {
            return $this->conexion->lastInsertId();
        }
        return null;
    }
}

// Instanciación del objeto de conexión con los parámetros locales
// Parámetros: servidor, usuario, contraseña, nombre_base_de_datos
$pdo = new Conexion("localhost", "root", "", "examenes_online");
?>
