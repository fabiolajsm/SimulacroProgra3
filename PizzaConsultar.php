<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sabor = $_POST['sabor'];
    $tipo = $_POST['tipo'];

    $jsonFile = 'Pizza.json';
    $pizzaData = [];

    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $pizzaData = json_decode($jsonData, true);

        if ($pizzaData === null) {
            echo 'Error: el archivo JSON no es válido.';
            return;
        }
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
