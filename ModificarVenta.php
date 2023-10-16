<?php
include('ManejadorArchivos.php');

// Recuperar datos del cuerpo de la solicitud PUT
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['numero_pedido']) && isset($input['email_usuario']) &&
    isset($input['sabor']) && isset($input['tipo']) && isset($input['cantidad'])) {
    $numeroPedido = $input['numero_pedido'];
    $emailUsuario = $input['email_usuario'];
    $sabor = $input['sabor'];
    $tipo = $input['tipo'];
    $cantidad = $input['cantidad'];

    $manejadorArchivos = new ManejadorArchivos('Pizza.json');
    $ventas = $manejadorArchivos->leer();
    if (empty($ventas)) {
        echo 'Error: el archivo JSON no es válido.';
        return;
    }

    $ventaEncontrada = false;
    foreach ($ventas as &$venta) {
        if ($venta['numero_pedido'] == $numeroPedido && $venta['email_usuario'] == $emailUsuario) {
            $venta['sabor'] = $sabor;
            $venta['tipo'] = $tipo;
            $venta['cantidad'] = $cantidad;
            $ventaEncontrada = true;
            break;
        }
    }
    $manejadorArchivos->guardar($ventas);
    if ($ventaEncontrada) {
        echo "Venta modificada con éxito.";
    } else {
        echo "La venta no existe. No se puede modificar.";
    }
} else {
    echo "Faltan parámetros en la solicitud PUT.";
}
?>
