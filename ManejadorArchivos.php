<?php
class ManejadorArchivos {
    private $urlArchivo;

    public function __construct($urlArchivo) {
        $this->urlArchivo = $urlArchivo; 
    }

    public function leer(){
        if (file_exists($this->urlArchivo)) {
            $jsonData = file_get_contents($this->urlArchivo); 
            return json_decode($jsonData, true);
        } else {
            return [];
        }
    }

    public function guardar($data) {
        $jsonData = json_encode($data);
        file_put_contents($this->urlArchivo, $jsonData);
    }
}
?>
