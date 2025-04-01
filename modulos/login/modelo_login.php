<?php

class Modelo_login
{ 
    private $conexion;
    public $email; 

    public function __construct($conexion, $email)
    {

        $this->conexion = $conexion;
        $this->email = $email;
    }
    /**
     * @$conexion -> conexion a la base de datos
     * @$email -> email del usuario a buscar en la base de datos  
     * 
     */
    public function getuser()
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(":email", $this->email, PDO::PARAM_STR);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
            /**
             * @return $sentencia -> la fila coincidida con el email o null 
             */

        } catch (PDOException $e) {
            echo "conexion sin exito:" .$e;
            return false;
        }

    }
    public function getAspirantes()
    {
        try {
            $sql = "SELECT * FROM estudiantes WHERE email = :email";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(":email", $this->email, PDO::PARAM_STR);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
            /**
             * @return -> la fila estudiante coincidente con gmail
             */

        } catch (PDOException $e) {
            echo "conexion sin exito:" .$e;
            return false;
        }

    }


}


?>