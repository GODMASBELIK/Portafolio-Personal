<?php
require 'PHPMailer-master/vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado y autoload incluido
require_once 'DAO.php';
require_once("conf.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dao = new DAO('coches.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriculas'])) {
    $matriculas = $_POST['matriculas'];

    $vehiculosEliminados = [];
    $dao = new DAO();

    $imagenesVehiculos = []; // Array para almacenar las imágenes de los vehículos eliminados

    foreach ($matriculas as $matricula) {
        if ($dao->quitar($matricula)) {
            $vehiculosEliminados[] = $matricula;

            // Suponiendo que las imágenes están en una carpeta llamada 'imagenes' y el nombre de la imagen es igual a la matrícula del vehículo
            $rutaImagen = 'imagenes/' . $matricula . '.jpg'; // O el formato adecuado
            if (file_exists($rutaImagen)) {
                $imagenesVehiculos[] = $rutaImagen;
            }
        }
    }

    // Log de los vehículos eliminados
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
            'adjuntos' => $imagenesVehiculos,  // Aquí se agregan las imágenes como adjuntos
            'asunto' => 'Vehiculos eliminados',
            'cuerpo' => 'Se han dado de baja los siguientes vehículos: ' . implode(', ', $vehiculosEliminados)
        ];

        foreach ($mensajeObj['destinatario'] as $dest) {
            $mail->addAddress($dest);
        }

        // Adjuntar imágenes
        foreach ($mensajeObj['adjuntos'] as $adjunto) {
            $mail->addAttachment($adjunto); // Adjuntamos cada imagen
        }

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $mensajeObj['asunto'];
        $mail->Body = $mensajeObj['cuerpo'];

        // Enviar el correo
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}\n";
    }
} else {
    echo "<p>No se seleccionaron vehículos para eliminar.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos Eliminados</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #ecf0f1;
        }

        aside {
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
            color: #ecf0f1;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .menu {
            list-style-type: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            text-decoration: none;
            color: white;
            padding: 10px;
            display: block;
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

        h1 {
            text-align: center;
            margin-bottom: 1em;
            font-size: 1.8em;
            color: #2c3e50;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 1em;
        }

        ul li {
            background-color: #34495e;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }

        img {
            width: 100px;  /* Ajusta según el tamaño necesario */
            height: auto;
            display: block;
            margin-top: 10px;
        }

        footer {
            position: absolute;
            text-align: center;
            padding: 1em;
            background-color: rgb(0, 0, 0);
            color: white;
            bottom: 0;
            width: 100%;
            z-index: -1;
        }
    </style>
</head>

<body>
    <aside>
        <h2>Menú</h2>
        <ul class="menu">
            <li><a href="menu.php">Inicio</a></li>
            <li><a href="baja.php">Eliminar Vehículos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
    </aside>
    <main>
        <h1>Resultado de Baja de Vehículos</h1>

        <?php
        // Mostrar vehículos eliminados o un mensaje si no se han seleccionado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matriculas'])) {
            echo "<p>Los siguientes vehículos han sido dados de baja:</p>";
            echo "<ul>";
            foreach ($vehiculosEliminados as $matricula) {
                echo "<li>" . htmlspecialchars($matricula);

                // Mostrar la imagen del vehículo si existe
                $rutaImagen = 'imagenes/' . $matricula . '.jpg';  // O el formato adecuado
                if (file_exists($rutaImagen)) {
                    echo "<br><img src='" . $rutaImagen . "' alt='Imagen de " . $matricula . "'>";
                }

                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se seleccionaron vehículos para eliminar.</p>";
        }
        ?>

    </main>
    <footer>
        <p>&copy; 2025 Vehículos S.A. Todos los derechos reservados.</p>
    </footer>
</body>

</html>
