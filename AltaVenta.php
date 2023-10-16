<?php
include 'ManejadorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $sabor = $_POST['sabor'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    $archivoJson = 'Pizza.json';
    $manejadorArchivos = new ManejadorArchivos($archivoJson);

    $pizzaData = $manejadorArchivos->leer();
    if (empty($pizzaData)) {
        echo 'Error: el archivo JSON no es válido.';
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
                $numeroDePedido = $pizza['numeroDePedidos'] ?? 0;
                $numeroDePedido++;
                $pizza['numeroDePedidos'] = $numeroDePedido;

                // Registrar la venta
                $nuevaVenta = [
                    'id' => count($pizza['ventas']) + 1, // ID autoincremental de la venta
                    'numeroDePedido' => $numeroDePedido, // Número de pedido
                    'email' => $email,
                    'sabor' => $sabor,
                    'tipo' => $tipo,
                    'cantidad' => $cantidad,
                    'fecha' => date('Y-m-d H:i:s')
                ];
                $pizza['ventas'][] = $nuevaVenta;
                $pizza['cantidad'] -= $cantidad;

                // Guardar la imagen
                $nombreImagen = $tipo . $sabor . strstr($email, '@', true) . date('YmdHis') . '.jpg';
                $rutaImagen = 'ImagenesDeLaVenta/' . $nombreImagen;
                if (isset($_FILES['imagen']) && move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
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
    } else {
        $manejadorArchivos->guardar($pizzaData);
    }
} else {
    http_response_code(405); // Método no permitido
}
?>