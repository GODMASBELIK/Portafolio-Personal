<?php
require_once 'DAO.php';
require_once 'Vehiculo.php';
include_once("conf.php");

// Cargar los vehículos desde el archivo JSON
$dao = new DAO('coches.json');
$vehiculos = $dao->listar();

// Buscar por matrícula si se envió el parámetro
$busqueda = isset($_GET['matricula']) ? $_GET['matricula'] : '';
if ($busqueda !== '') {
    $vehiculos = array_filter($vehiculos, function ($vehiculo) use ($busqueda) {
        return stripos($vehiculo['matricula'], $busqueda) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos en Venta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #ecf0f1;
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
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .search-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            font-size: 1em;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-container button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 300px;
            text-align: center;
            padding: 20px;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card h3 {
            margin-top: 15px;
            color: #2c3e50;
        }

        .card p {
            color: #7f8c8d;
            margin: 10px 0;
        }

        .card .price {
            font-size: 1.2em;
            font-weight: bold;
            color: #e74c3c;
            margin: 10px 0;
        }

        .card a {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .card a:hover {
            background-color: #2980b9;
        }

        .no-vehicles {
            position: absolute;
            margin: 0 auto;
            text-align: center;
            font-size: 1.2em;
            color: #e74c3c;
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
                <a href="menu.php" class="toggle">Inicio</a>
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
        <h1>Vehículos en Venta</h1>

        <div class="search-container">
            <form method="GET" action="menu.php">
                <input type="text" name="matricula" placeholder="Buscar por matrícula" value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="cards-container">
            <?php if (empty($vehiculos)) : ?>
                <div class="no-vehicles">
                    No se encontraron vehículos para esta búsqueda.
                </div>
            <?php else : ?>
                <?php foreach ($vehiculos as $vehiculo) : ?>
                    <div class="card">
                        <?php if (!empty($vehiculo['imagen'])) : ?>
                            <img src="<?php echo htmlspecialchars($vehiculo['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($vehiculo['modelo']); ?>">
                        <?php else : ?>
                            <img src="default.jpg" alt="Imagen no disponible">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($vehiculo['marca']) . ' ' . htmlspecialchars($vehiculo['modelo']); ?></h3>
                        <p>Matrícula: <?php echo htmlspecialchars($vehiculo['matricula']); ?></p>
                        <p>Potencia: <?php echo htmlspecialchars($vehiculo['potencia']); ?> CV</p>
                        <p>Velocidad Máxima: <?php echo htmlspecialchars($vehiculo['velocidadMax']); ?> km/h</p>
                        <a href="editar.php?matricula=<?php echo urlencode($vehiculo['matricula']); ?>">Ver Detalles</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
