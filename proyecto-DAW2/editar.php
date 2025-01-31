<?php
require_once 'DAO.php';
require_once 'Vehiculo.php';
require_once 'conf.php';

// Obtener la matrícula del vehículo a editar
if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];

    // Crear una instancia de DAO para obtener el vehículo
    $dao = new DAO('coches.json');
    $vehiculo = $dao->buscarPorMatricula($matricula);

    // Si no se encuentra el vehículo, redirigir
    if (!$vehiculo) {
        header('Location: menu.php');
        exit;
    }
} else {
    header('Location: menu.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veh = new Vehiculo($_POST["matricula"], $_POST["marca"], $_POST["modelo"]);
    $veh->potencia = $_POST["potencia"];
    $veh->velocidadMax = $_POST["velocidadMax"];

    $rutaDestino = $vehiculo['imagen']; // Mantener la imagen actual si no se sube una nueva
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $nombreArchivo = "{$veh->matricula}_" . $archivo['name'];
        $directorioDestino = 'img/';
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }
        $rutaDestino = $directorioDestino . basename($nombreArchivo);
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($archivo['type'], $tiposPermitidos) && $archivo['size'] <= 2 * 1024 * 1024) {
            move_uploaded_file($archivo['tmp_name'], $rutaDestino);
        }
    }

    $veh->imgPath = $rutaDestino;
    if ($dao->editar($matricula, $veh)) {
        header('Location: baja.php');
        exit;
    } else {
        echo "<p>Error al guardar los cambios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vehículo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
        }

        aside {
            top: 0;
            position: sticky;
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        aside h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        .menu {
            list-style: none;
        }

        .menu a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: #34495e;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        footer {
            position: absolute;
            text-align: center;
            padding: 1em;
            background-color:rgb(0, 0, 0);
            color: white;
            bottom: 0;
            width: 100%;
            z-index: -1;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="file"] {
            padding: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .img-preview {
            max-width: 100px;
            display: block;
            margin-top: 10px;
        }

        .link-back {
            text-align: center;
            margin-top: 20px;
        }

        .link-back a {
            text-decoration: none;
            color: #007bff;
        }

        .link-back a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <aside>
        <h2>Menú</h2>
        <ul class="menu">
            <a href="menu.php">Inicio</a>
            <li><a href="baja.php">Listado de Vehículos</a></li>
            <li><a href="alta.php">Agregar Vehículo</a></li>
        </ul>
    </aside>

    <main>
        <h1>Editar Vehículo</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($vehiculo['matricula']); ?>" readonly>

            <label for="modelo">Modelo:</label>
            <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($vehiculo['modelo']); ?>">

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($vehiculo['marca']); ?>">

            <label for="potencia">Potencia (CV):</label>
            <input type="number" id="potencia" name="potencia" value="<?php echo htmlspecialchars($vehiculo['potencia']); ?>">

            <label for="velocidadMax">Velocidad Máxima (km/h):</label>
            <input type="number" id="velocidadMax" name="velocidadMax" value="<?php echo htmlspecialchars($vehiculo['velocidadMax']); ?>">

            <label for="imagen">Imagen (si desea cambiarla):</label>
            <input type="file" id="imagen" name="imagen">

            <?php if (!empty($vehiculo['imagen'])) : ?>
                <div>
                    <label>Imagen Actual:</label>
                    <img src="<?php echo htmlspecialchars($vehiculo['imagen']); ?>" alt="Imagen actual" class="img-preview">
                </div>
            <?php endif; ?>

            <button type="submit">Guardar Cambios</button>
        </form>

        <div class="link-back">
            <p><a href="baja.php">Volver al listado</a></p>
        </div>
    </main>
</body>

</html>
