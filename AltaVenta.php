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
                if (isset($pizza['numeroDePedidos'])) {
                    $numeroDePedido = $pizza['numeroDePedidos'];
                }
                $numeroDePedido++;
                $pizza['numeroDePedidos'] = $numeroDePedido;

                // Registrar la venta
                $newSale = [
                    'id' => count($pizza['ventas']) + 1, // ID autoincremental de la venta
                    'numeroDePedido' => $numeroDePedido, // Número de pedido
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
                $imageName = $tipo . $sabor . strstr($email, '@', true) . date('YmdHis') . '.jpg';
                $imagePath = 'ImagenesDeLaVenta/' . $imageName;

                if (isset($_FILES['imagen']) && move_uploaded_file($_FILES['imagen']['tmp_name'], $imagePath)) {
                    echo 'Venta registrada con éxito y la imagen se guardó correctamente.';
                } else {
                    echo 'Venta registrada con éxito, pero hubo un problema al guardar la imagen.';
                }
            } else {
                echo 'Error: No hay suficiente stock disponible.';
                return;
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
