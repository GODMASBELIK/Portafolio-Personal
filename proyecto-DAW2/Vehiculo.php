<?php

class Vehiculo {
    private $matricula;
    private  $marca ;
    private  $modelo ;
    private  $potencia ;
    private  $velocidadMax ;
    private  $imgPath;

    function __construct($matricula, $marca, $modelo) {
        $this->matricula = $matricula;
        $this->marca = $marca;
        $this->modelo = $modelo;
    }

    function __get($prop) {
        if(property_exists($this,$prop)) {
            return $this->$prop;
        }
    }

    function __set($prop, $val) {
        if(property_exists($this,$prop)) {
            $this->$prop = $val;
        }
    }
    
}



?>