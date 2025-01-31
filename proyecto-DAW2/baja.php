<?php
require_once 'DAO.php';
require_once 'Vehiculo.php';
include_once("conf.php");
require 'PHPMailer-master/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dao = new DAO('coches.json');
$vehiculos = $dao->listar();

$busqueda = isset($_GET['matricula']) ? $_GET['matricula'] : '';
if ($busqueda !== '') {
    $vehiculos = array_filter($vehiculos, function ($vehiculo) use ($busqueda) {
        return stripos($vehiculo['matricula'], $busqueda) !== false;
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriculas'])) {
    $matriculas = $_POST['matriculas'];

    $vehiculosEliminados = [];
    $dao = new DAO();

    $imagenesVehiculos = []; 

    foreach ($matriculas as $matricula) {
        if ($dao->quitar($matricula)) {
            $vehiculosEliminados[] = $matricula;

            $rutaImagen = 'imagenes/' . $matricula . '.jpg'; 
            if (file_exists($rutaImagen)) {
                $imagenesVehiculos[] = $rutaImagen;
            }
        }
    }

    $logFile = 'log/bajas.log';
    $logMessage = date('Y-m-d H:i:s') . " - Vehículos eliminados: " . implode(', ', $vehiculosEliminados) . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Alexandrisman18@gmail.com'; 
        $mail->Password = 'midy cjmy lyar qtvw'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('Alexandrisman18@gmail.com', 'auto-reply');

        $mensajeObj = [
            'destinatario' => ['Alexandrisman18@gmail.com'], 
            'adjuntos' => $imagenesVehiculos,  
            'asunto' => 'Vehiculos eliminados',
            'cuerpo' => 'Se han dado de baja los siguientes vehículos: ' . implode(', ', $vehiculosEliminados)
        ];

        foreach ($mensajeObj['destinatario'] as $dest) {
            $mail->addAddress($dest);
        }

        foreach ($mensajeObj['adjuntos'] as $adjunto) {
            $mail->addAttachment($adjunto); 
        }

        $mail->isHTML(true);
        $mail->Subject = $mensajeObj['asunto'];
        $mail->Body = $mensajeObj['cuerpo'];

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}\n";
    }

    header("Location: baja.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Vehículos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            display: flex;
        }

        aside {
            top: 0;
            position: sticky;
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            overflow: auto;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        aside h2 {
            text-align: center;
            font-size: 1.5em;
            color: #ecf0f1;
            margin-bottom: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 10px;
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

        .submenu {
            list-style: none;
            padding-left: 20px;
            display: none;
        }

        .menu li.active .submenu {
            display: block;
        }

        .menu .submenu a {
            font-size: 0.9em;
            background-color: #34495e;
        }

        .menu .submenu a:hover {
            background-color: #3c556e;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], button {
            padding: 10px;
            font-size: 1em;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        td img {
            max-width: 100px;
            border-radius: 5px;
        }

        td {
            color: #2c3e50;
        }

        td a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 1em;
        }

        ul li:not(.menu > *) {
            background-color: #34495e;
            color: white;
            padding: 10px;
            margin: 5px 0;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <aside>
        <h2>Menú</h2>
        <ul class="menu">
            <li><a href="menu.php">Inicio</a></li>
            <li>
                <a href="#" class="toggle">Vehículos</a>
                <ul class="submenu">
                    <li><a href="alta.php">Alta</a></li>
                    <li><a href="baja.php">Listado</a></li>
                </ul>
            </li>
        </ul>
    </aside>

    <main>
        <h1>Listado de Vehículos</h1>

        <form method="GET" action="baja.php">
            <label for="matricula">Buscar por matrícula:</label><br>
            <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>
       
        <form method="POST" action="baja.php">
            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Matrícula</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Potencia</th>
                        <th>Velocidad Máxima</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vehiculos)) : ?>
                        <tr>
                            <td colspan="8">No se encontraron vehículos</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($vehiculos as $vehiculo) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="matriculas[]" value="<?php echo htmlspecialchars($vehiculo['matricula']); ?>">
                                </td>
                                <td><?php echo htmlspecialchars($vehiculo['matricula']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['marca']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['potencia']); ?> CV</td>
                                <td><?php echo htmlspecialchars($vehiculo['velocidadMax']); ?> km/h</td>
                                <td>
                                    <?php if (!empty($vehiculo['imagen'])) : ?>
                                        <img src="<?php echo htmlspecialchars($vehiculo['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($vehiculo['modelo']); ?>">
                                    <?php else : ?>
                                        No disponible
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="editar.php?matricula=<?php echo urlencode($vehiculo['matricula']); ?>">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit">Dar de Baja Seleccionados</button>
        </form>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriculas'])) {
                echo "<p>Los siguientes vehículos han sido dados de baja:</p>";
                echo "<ul>";
                foreach ($vehiculosEliminados as $matricula) {
                    echo "<li>" . htmlspecialchars($matricula);
    
                    $rutaImagen = 'imagenes/' . $matricula . '.jpg';  // O el formato adecuado
                    if (file_exists($rutaImagen)) {
                        echo "<br><img src='" . $rutaImagen . "' alt='Imagen de " . $matricula . "'>";
                    }
    
                    echo "</li>";
                }
                echo "</ul>";
            } else {
            }

        ?>
    </main>

    <script>
        document.querySelectorAll('.toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const parent = toggle.parentElement;
                parent.classList.toggle('active');
            });
        });
    </script>
</body>

</html>
