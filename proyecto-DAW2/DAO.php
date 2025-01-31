<?php

require_once("Vehiculo.php");

class DAO {

    private $archivo = "coches.json";

    function crear(Vehiculo $veh) {
        $contenido = file_get_contents($this->archivo);

        $vehiculos = [];
        if (!empty($contenido)) {
            $vehiculos = json_decode($contenido, true);
            if ($vehiculos === null) {
                $vehiculos = [];
            }
        }

        foreach ($vehiculos as $vehiculo) {
            if ($vehiculo["matricula"] === $veh->matricula) {
                return false; 
            }
        }

        $nuevoVehiculo = [
            "matricula" => $veh->matricula,
            "modelo" => $veh->modelo,
            "marca" => $veh->marca,
            "potencia" => $veh->potencia,
            "velocidadMax" => $veh->velocidadMax,
            "imagen" => $veh->imgPath,
        ];

        $vehiculos[] = $nuevoVehiculo;

        $json = json_encode($vehiculos, JSON_PRETTY_PRINT);
        file_put_contents($this->archivo, $json);

        return true;
    }

    function quitar($matricula) {
        $contenido = file_get_contents($this->archivo);
   
        $vehiculos = [];
        if (!empty($contenido)) {
            $vehiculos = json_decode($contenido, true);
            if ($vehiculos === null) {
                $vehiculos = [];
            }
        }
   
        $vehiculosFiltrados = array_filter($vehiculos, function ($vehiculo) use ($matricula) {
            return $vehiculo['matricula'] !== $matricula;
        });
   
        if (count($vehiculos) !== count($vehiculosFiltrados)) { // Si hay un cambio
            $vehiculosFiltrados = array_values($vehiculosFiltrados);
            $json = json_encode($vehiculosFiltrados, JSON_PRETTY_PRINT);
            file_put_contents($this->archivo, $json);
            return true;
        }
   
        return false;
    }
   

    function editar($matricula, Vehiculo $nuevoVehiculo) {
        $contenido = file_get_contents($this->archivo);
        $vehiculos = [];
    
        if (!empty($contenido)) {
            $vehiculos = json_decode($contenido, true);
            if ($vehiculos === null) {
                $vehiculos = [];
            }
        }
    
        // Eliminamos el vehículo viejo con la matrícula proporcionada
        $vehiculos = array_filter($vehiculos, function($vehiculo) use ($matricula) {
            return $vehiculo['matricula'] !== $matricula;
        });
    
        // Convertimos el objeto Vehiculo a un array asociativo para agregarlo
        $nuevoVehiculoArray = [
            'matricula' => $nuevoVehiculo->matricula,
            'modelo' => $nuevoVehiculo->modelo,
            'marca' => $nuevoVehiculo->marca,
            'potencia' => $nuevoVehiculo->potencia,
            'velocidadMax' => $nuevoVehiculo->velocidadMax,
            'imagen' => $nuevoVehiculo->imgPath
        ];
    
        // Añadimos el nuevo vehículo
        $vehiculos[] = $nuevoVehiculoArray;
    
        // Guardamos la lista actualizada de vehículos en el archivo
        $json = json_encode($vehiculos, JSON_PRETTY_PRINT);
        file_put_contents($this->archivo, $json);
    
        return true;
    }
    
    
    
    

    function listar() {
        $contenido = file_get_contents($this->archivo);

        $vehiculos = [];
        if (!empty($contenido)) {
            $vehiculos = json_decode($contenido, true);
            if ($vehiculos === null) {
                $vehiculos = [];
            }
        }

        return $vehiculos;
    }

    function buscarPorMatricula($matricula) {
        $contenido = file_get_contents($this->archivo);

        $vehiculos = [];
        if (!empty($contenido)) {
            $vehiculos = json_decode($contenido, true);
            if ($vehiculos === null) {
                $vehiculos = [];
            }
        }

        foreach ($vehiculos as $vehiculo) {
            if ($vehiculo['matricula'] === $matricula) {
                return $vehiculo; 
            }
        }

        return null; 
    }
}

?>
