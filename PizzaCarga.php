<?php
include 'ManejadorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sabor = $_GET['sabor'];
    $precio = $_GET['precio'];
    $tipo = $_GET['tipo'];
    $cantidad = $_GET['cantidad'];

    if ($tipo !== "molde" && $tipo !== "piedra") {
        echo 'Error: El tipo debe ser "molde" o "piedra".';
        return;
    }

    $archivoJson = 'Pizza.json';
    $manejadorArchivos = new ManejadorArchivos($archivoJson);
    $pizzaData = $manejadorArchivos->leer();
    if (empty($pizzaData)) {
        echo 'Error: el archivo JSON no es válido.';
        return;
    }
    // Generar un identificador autoincremental emulado
    $maxId = 0;
    foreach ($pizzaData as $pizza) {
        $maxId = max($maxId, $pizza['id']);
    }
    $nuevoId = $maxId + 1;

    $existeKeyEnPizza = -1;
    foreach ($pizzaData as $key => $pizza) {
        if ($pizza['sabor'] === $sabor && $pizza['tipo'] === $tipo) {
            $existeKeyEnPizza = $key;
            break;
        }
    }

    if ($existeKeyEnPizza >= 0) {
        $pizzaData[$existeKeyEnPizza]['precio'] = $precio;
        $pizzaData[$existeKeyEnPizza]['cantidad'] += $cantidad;
    } else {
        $nuevaPizza = [
            'id' => $nuevoId,
            'sabor' => $sabor,
            'precio' => $precio,
            'tipo' => $tipo,
            'cantidad' => $cantidad
        ];
        $pizzaData[] = $nuevaPizza;
    }

    // Guardar los datos actualizados en Pizza.json
    $manejadorArchivos->guardar($pizzaData);
    echo 'Datos actualizados con éxito.';
} else {
    echo 'Método no permitido';
}
?>
