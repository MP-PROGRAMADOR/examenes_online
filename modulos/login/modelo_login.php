<?php

class Modelo_login
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    public function getuser()
    {
        try {
            $sql = "SELECT email, password, rol FROM usuarios";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);


        } catch (PDOException $e) {
            echo "conexion sin exito:" .$e;
            return false;
        }

    }
    public function getAspirantes()
    {
        try {
            $sql = "SELECT email, codigo_registro_examen FROM estudiantes";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);


        } catch (PDOException $e) {
            echo "conexion sin exito:" .$e;
            return false;
        }

    }


}


?>