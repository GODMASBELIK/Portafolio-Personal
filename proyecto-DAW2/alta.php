<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Vehículos</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            display: flex;
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
            margin: 0;
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

        .submenu {
            list-style-type: none;
            margin: 0;
            padding-left: 20px;
            display: none;
        }

        .menu li.active .submenu {
            display: block;
        }

        .menu li .submenu a {
            font-size: 0.9em;
            background-color: #34495e;
        }

        .menu li .submenu a:hover {
            background-color: #3c556e;
        }

        .toggle {
            cursor: pointer;
            user-select: none;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        form {
            background-color: #2c3e50;
            padding: 2em;
            width: 60%;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            color: #ecf0f1;
        }

        .form-group {
            margin-bottom: 1.5em;
        }

        label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
            color: #bdc3c7;
        }

        input,
        select {
            width: 100%;
            padding: 0.8em;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            background-color: #34495e;
            color: #ecf0f1;
        }

        input[type="file"] {
            background-color: #34495e;
            padding: 0.7em;
        }

        input:focus,
        select:focus {
            outline: none;
            background-color: #3c556e;
        }

        input[type="submit"] {
            width: 100%;
            padding: 1em;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        h1 {
            text-align: center;
            margin-bottom: 1em;
            font-size: 1.8em;
            color: #ecf0f1;
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
    </style>
</head>

<body>
    <aside>
        <h2>Menú</h2>
        <ul class="menu">
            <li>
                <a href="menu.php">Inicio</a>
            </li>
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

        <form action="procesar.php" method="POST" enctype="multipart/form-data">
            <h1>Formulario de Vehículos</h1>
            <div class="form-group">
            <?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    switch ($error) {
        case 'matricula_duplicada':
            echo "<p style='color:red;'>Error: La matrícula ya está registrada.</p>";
            break;
        case 'crear_vehiculo':
            echo "<p style='color:red;'>Error: No se pudo crear el vehículo.</p>";
            break;
        case 'faltan_datos':
            echo "<p style='color:red;'>Error: Faltan datos importantes.</p>";
            break;
    }
}

?>
                <label for="matricula">Matrícula</label>
                <input type="text" id="matricula" name="matricula" required pattern="[A-Za-z0-9]{4}[A-Za-z]{3}" title="La matrícula debe tener el formato XXXX-XXX (ejemplo: ABCD-123)">
            </div>
            <div class="form-group">
                <label for="modelo">Modelo</label>
                <input type="text" id="modelo" name="modelo" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <select name="marca" id="marca">
                    <?php
                    // Cargar las marcas desde el archivo de texto
                    foreach (file('marcas_vehiculos.txt') as $line) {
                        $line = trim($line); // Eliminar posibles saltos de línea u otros caracteres innecesarios
                        echo "<option value=\"$line\">$line</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="potencia">Potencia (en CV)</label>
                <input type="number" id="potencia" name="potencia" required min="0" step="0.1">
            </div>
            <div class="form-group">
                <label for="velocidad">Velocidad Máxima (km/h)</label>
                <input type="number" id="velocidad" name="velocidad" required min="0" step="1">
            </div>
            <div class="form-group">
                <?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    switch ($error) {
        case 'archivotipo':
            echo "<p style='color:red;'>Error: Solo se acepta JPG, PNG, PDF</p>";
            break;
    }
}
                ?>
                <label for="imagen">Imagen del Vehículo (Opcional)</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>
            <input type="submit" value="Enviar">
        </form>
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

