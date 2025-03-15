<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
</head>
<body>
    <h2>Registro de Usuarios</h2>
    <form action="php/registrar_usuarios.php" method="POST">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" name="nombre" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>

       
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="estudiante">Estudiante</option>
            <option value="examinador">Examinador</option>
            <option value="administrador">Administrador</option>
        </select>


        <label for="email">fecha de registro:</label>
        <input type="date" name="fecha_registro" required>

        <label for="email">Edad:</label>
        <input type="num" name="edad" required min="15">


        <label for="email">Dip:</label>
        <input type="num" name="dip" required >

        <label for="centro">centro de procedencia:</label>
        <select name="centro" required>
            <option value="estudiante">Estudiante</option>
            <option value="examinador">Examinador</option>
            <option value="administrador">Administrador</option>
        </select>

        <button type="submit">Registrar Usuario</button>
    </form>
</body>
</html>
