<?php
include 'ManejadorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sabor = $_POST['sabor'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    if ($tipo !== "molde" && $tipo !== "piedra") {
        echo 'Error: El tipo debe ser "molde" o "piedra".';
        return;
    }

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaImagenes = '/ImagenesDePizzas/';
        $nombreImagen = $tipo . '_' . $sabor . '_' . uniqid() . '.jpg';
        $rutaCompleta = __DIR__ . $rutaImagenes . $nombreImagen;

        // Mover la imagen al directorio de imágenes
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            // Continuar con el proceso de almacenamiento de datos en JSON
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
                    'cantidad' => $cantidad,
                    'imagen' => $rutaImagenes . $nombreImagen // Almacena la ruta de la imagen en el JSON
                ];
                $pizzaData[] = $nuevaPizza;
            }

            // Guardar los datos actualizados en Pizza.json
            $manejadorArchivos->guardar($pizzaData);
            echo 'Datos actualizados con éxito.';
        } else {
            echo 'Error al mover la imagen a la carpeta de imágenes.';
        }
    } else {
        echo 'Error: No se proporcionó una imagen válida.';
    }
} else {
    echo 'Método no permitido';
}
?>
