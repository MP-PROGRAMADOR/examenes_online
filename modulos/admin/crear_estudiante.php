<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Escuela de Conducción</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            box-sizing: border-box;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-group input[type="submit"]:active {
            background-color: #004080;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Registrar Escuela de Conducción</h2>
        <form action="/ruta-a-tu-servidor" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre de la Escuela</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="codigo_escuela">Código de la Escuela</label>
                <input type="text" id="codigo_escuela" name="codigo_escuela" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="entidad_trafico">Entidad de Tráfico</label>
                <select id="entidad_trafico" name="entidad_trafico_id" required>
                    <option value="">Seleccione una entidad</option>
                    <!-- Aquí puedes insertar las entidades dinámicamente -->
                    <option value="1">Entidad 1</option>
                    <option value="2">Entidad 2</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrar Escuela">
            </div>
        </form>
    </div>

</body>
</html>
