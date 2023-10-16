<?php
include 'ManejadorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sabor = $_POST['sabor'];
    $tipo = $_POST['tipo'];

    $archivoJson = 'Pizza.json';
    $manejadorArchivos = new ManejadorArchivos($archivoJson);
    $pizzaData = $manejadorArchivos->leer();
    if (empty($pizzaData)) {
        echo 'Error: el archivo JSON no es válido.';
        return;
    }
    $pizzaExists = false;

    foreach ($pizzaData as $pizza) {
        if ($pizza['sabor'] === $sabor && $pizza['tipo'] === $tipo) {
            $pizzaExists = true;
            break;
        }
    }

    if ($pizzaExists) {
        echo 'Si Hay';
    } else {
        echo 'No Hay';
    }
} else {
    echo 'Método no permitido';
}
?>
