<?php
include('ManejadorArchivos.php');

parse_str(file_get_contents('php://input'), $data);
if (isset($data['numeroDePedido'])) {
    $numeroPedido = $data['numeroDePedido'];
    $carpetaBackupVentas = '/BACKUPVENTAS';

    $manejadorArchivos = new ManejadorArchivos('Pizza.json');
    $ventas = $manejadorArchivos->leer();
    if (empty($ventas)) {
        echo 'Error: el archivo JSON no es válido.';
        return;
    }
    
    // Buscar la venta por número de pedido
    $ventaEncontrada = false;
    $indiceVenta = null;
    foreach ($ventas as $indice => $venta) {
        if ($venta['numeroDePedido'] == $numeroPedido) {
            $ventaEncontrada = true;
            $indiceVenta = $indice;
            break;
        }
    }

    if ($ventaEncontrada) {
        // Mover la foto de la venta a la carpeta de respaldo
        $rutaFoto = $ventas[$indiceVenta]['foto'];
        $nombreArchivo = basename($rutaFoto);
        $rutaDestino = $carpetaBackupVentas . '/' . $nombreArchivo;
        
        if (rename($rutaFoto, $rutaDestino)) {
            // Eliminar la venta del array
            unset($ventas[$indiceVenta]);
            $ventas = array_values($ventas); // Reindexar el array
            $manejadorArchivos->guardar($ventas);
            echo "Venta eliminada con éxito y la foto se ha movido a la carpeta de respaldo.";
        } else {
            echo "Error al mover la foto a la carpeta de respaldo.";
            return;
        }
    } else {
        echo "La venta no existe. No se puede eliminar.";
    }
} else {
    echo "Falta el número de pedido en la solicitud DELETE.";
}
?>
