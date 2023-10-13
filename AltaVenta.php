<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $sabor = $_POST['sabor'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

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
    $pizzaExists = false;
    foreach ($pizzaData as &$pizza) {
        if ($pizza['sabor'] === $sabor && $pizza['tipo'] === $tipo) {
            if (!isset($pizza['ventas'])) {
                $pizza['ventas'] = []; 
            }
            if ($pizza['cantidad'] >= $cantidad) {
                $pizzaExists = true;
                // Simular un número de pedido autoincremental
                $numeroDePedido = 0;
                if (isset($pizza['numeroDePedido'])) {
                    $numeroDePedido = $pizza['numeroDePedido'];
                }
                $nuevoNumeroDePedido = $numeroDePedido;
                $pizza['numeroDePedido'] = $numeroDePedido + 1;

                // Registrar la venta
                $newSale = [
                    'id' => count($pizza['ventas']) + 1, // ID autoincremental de la venta
                    'numero_pedido' => $nuevoNumeroDePedido, // Número de pedido
                    'email' => $email,
                    'sabor' => $sabor,
                    'tipo' => $tipo,
                    'cantidad' => $cantidad,
                    'fecha' => date('Y-m-d H:i:s')
                ];
                $pizza['ventas'][] = $newSale;
                $pizza['cantidad'] -= $cantidad;
                // Guardar en Pizza.json
                file_put_contents($jsonFile, json_encode($pizzaData, JSON_PRETTY_PRINT));
                echo 'Venta registrada con éxito.';
            } else {
                echo 'Error: No hay suficiente stock disponible.';
            }
            break;
        }
    }
    if (!$pizzaExists) {
        echo 'Error: El ítem no existe en Pizza.json o no hay stock suficiente.';
    }
} else {
    echo 'Método no permitido';
}
?>
