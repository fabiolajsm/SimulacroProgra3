<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sabor = $_GET['sabor'];
    $precio = $_GET['precio'];
    $tipo = $_GET['tipo'];
    $cantidad = $_GET['cantidad'];

    if ($tipo !== "molde" && $tipo !== "piedra") {
        echo 'Error: El tipo debe ser "molde" o "piedra".';
        return;
    }

    $jsonFile = 'Pizza.json';
    $pizzaData = [];

    // Verificar la validez del archivo JSON antes de intentar leerlo
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $pizzaData = json_decode($jsonData, true);

        if ($pizzaData === null) {
            echo 'Error: el archivo JSON no es válido.';
            return;
        }
    }

    // Generar un identificador autoincremental emulado
    $maxId = 0;
    foreach ($pizzaData as $pizza) {
        $maxId = max($maxId, $pizza['id']);
    }
    $newId = $maxId + 1;

    $existingPizzaKey = -1;
    foreach ($pizzaData as $key => $pizza) {
        if ($pizza['sabor'] === $sabor && $pizza['tipo'] === $tipo) {
            $existingPizzaKey = $key;
            break;
        }
    }

    if ($existingPizzaKey >= 0) {
        $pizzaData[$existingPizzaKey]['precio'] = $precio;
        $pizzaData[$existingPizzaKey]['cantidad'] += $cantidad;
    } else {
        $newPizza = [
            'id' => $newId,
            'sabor' => $sabor,
            'precio' => $precio,
            'tipo' => $tipo,
            'cantidad' => $cantidad
        ];
        $pizzaData[] = $newPizza;
    }

    // Guardar los datos actualizados en Pizza.json
    file_put_contents($jsonFile, json_encode($pizzaData));

    echo 'Datos actualizados con éxito.';
} else {
    echo 'Método no permitido';
}
?>
