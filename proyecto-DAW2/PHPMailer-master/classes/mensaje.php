<?php

    class Mensaje {
        private $destinatario;
        private $asunto;
        private $cuerpo;
        private $cc;
        private $cco;
        private $adjuntos;
    
        public function __construct($destinatario, $asunto, $cuerpo) {
            $this->destinatario = is_array($destinatario) ? $destinatario : [$destinatario];
            $this->asunto = $asunto;
            $this->cuerpo = $cuerpo;
            $this->cc = [];
            $this->cco = [];
            $this->adjuntos = [];
        }
    
        public function __get($propiedad) {
            if (property_exists($this, $propiedad)) {
                return $this->$propiedad;
            }
            return null;
        }
    
        public function __set($propiedad, $valor) {
            if (property_exists($this, $propiedad)) {
                $this->$propiedad = $valor;
            }
        }
    
        public function addDestinatario($email) {
            $this->destinatario[] = $email;
        }
    
        public function removeDestinatarioByIndex($index) {
            if (isset($this->destinatario[$index])) {
                unset($this->destinatario[$index]);
                $this->destinatario = array_values($this->destinatario);
            }
        }
    
        public function addCC($email) {
            $this->cc[] = $email;
        }
    
        public function removeCCByIndex($index) {
            if (isset($this->cc[$index])) {
                unset($this->cc[$index]);
                $this->cc = array_values($this->cc);
            }
        }
    
        public function addCCO($email) {
            $this->cco[] = $email;
        }
    
        public function removeCCOByIndex($index) {
            if (isset($this->cco[$index])) {
                unset($this->cco[$index]);
                $this->cco = array_values($this->cco);
            }
        }
    
        public function addAdjunto($filePath) {
            $this->adjuntos[] = $filePath;
        }
    
        public function removeAdjuntoByIndex($index) {
            if (isset($this->adjuntos[$index])) {
                unset($this->adjuntos[$index]);
                $this->adjuntos = array_values($this->adjuntos);
            }
        }
    }
?>
