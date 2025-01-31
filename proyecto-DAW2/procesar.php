<?php
require("DAO.php");
require_once("conf.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["matricula"])) {
        $oh = new DAO();

        // Imprimir datos de POST y FILES para depuración
        var_dump($_POST);
        var_dump($_FILES);

        $veh = new Vehiculo($_POST["matricula"], $_POST["marca"], $_POST["modelo"]);
        $veh->potencia = $_POST["potencia"];
        $veh->velocidadMax = $_POST["velocidad"];

        // Comprobar si se ha subido una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $archivo = $_FILES['imagen'];

            $nombreArchivo = "{$veh->matricula}_{$archivo['name']}";
            $tipoArchivo = $archivo['type'];
            $tmpArchivo = $archivo['tmp_name']; 
            $tamañoArchivo = $archivo['size'];
            $directorioDestino = 'img/';

            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0777, true); 
            }

            $rutaDestino = $directorioDestino . basename($nombreArchivo);

            $tiposPermitidos = ['image/jpeg', 'image/png', 'application/pdf'];
            if (in_array($tipoArchivo, $tiposPermitidos)) {
                $maxTamaño = 2 * 1024 * 1024; 
                if ($tamañoArchivo <= $maxTamaño) {
                    if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                        echo "El archivo se ha subido correctamente: " . $nombreArchivo;
                        $veh->imgPath = $rutaDestino;
                    } else {
                        echo "Hubo un error al mover el archivo.";
                        exit;
                    }
                } else {
                    echo "El archivo es demasiado grande. El tamaño máximo permitido es 2MB.";
                    exit;
                }
            } else {
                echo "Tipo de archivo no permitido. Solo se aceptan imágenes JPG, PNG y archivos PDF.";
            header("Location: alta.php?error=archivotipo");

                exit;
            }
        } else {
            // Si no se ha recibido ningún archivo, asignamos un valor null
            $veh->imgPath = null; // No hay imagen
        }

        if ($oh->crear($veh)) {
            echo "Vehículo creado correctamente.";
            header("Location: alta.php");
            exit;
        } else {
            echo "Error al crear el vehículo.";
            header("Location: alta.php?error=matricula_duplicada");
        }
    } else {
        echo "Faltan datos importantes.";
    }
} else {
    echo "Método no permitido.";
}
?>
