<?php
include 'ManejadorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    $archivoJson = 'Pizza.json';
    $manejadorArchivos = new ManejadorArchivos($archivoJson);
    $pizzaData = $manejadorArchivos->leer();
    if (empty($pizzaData)) {
        echo 'Error: el archivo JSON no es válido.';
        return;
    }

    switch ($action) {
        case 'a': // La cantidad de pizzas vendidas
            $totalPizzasVendidas = 0;
            foreach ($pizzaData as $pizza) {
                if (isset($pizza['ventas'])) {
                    foreach ($pizza['ventas'] as $venta) {
                        $totalPizzasVendidas += intval($venta['cantidad']);
                    }
                }
            }
            echo 'La cantidad total de pizzas vendidas es: ' . $totalPizzasVendidas;
            break;

        case 'b': // Listado de ventas entre dos fechas ordenado por sabor
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];

            $filteredSales = array();
            foreach ($pizzaData as $pizza) {
                if (isset($pizza['ventas'])) {
                    foreach ($pizza['ventas'] as $venta) {
                        $fechaVenta = $venta['fecha'];
                        if ($fechaVenta >= $startDate && $fechaVenta <= $endDate) {
                            $filteredSales[] = $venta;
                        }
                    }
                }
            }

            usort($filteredSales, function ($a, $b) {
                return strcmp($a['sabor'], $b['sabor']);
            });

            // Ahora $filteredSales contiene las ventas entre las fechas ordenadas por sabor.
            echo json_encode($filteredSales);
            break;

        case 'c': // Listado de ventas de un usuario ingresado
            $usuarioBuscado = $_POST['usuario'];

            $ventasUsuario = array();
            foreach ($pizzaData as $pizza) {
                if (isset($pizza['ventas'])) {
                    foreach ($pizza['ventas'] as $venta) {
                        if ($venta['email'] === $usuarioBuscado) {
                            $ventasUsuario[] = $venta;
                        }
                    }
                }
            }

            // Ahora $ventasUsuario contiene las ventas del usuario ingresado.
            echo json_encode($ventasUsuario);
            break;

        case 'd': // Listado de ventas de un sabor ingresado
            $saborBuscado = $_POST['sabor'];

            $ventasSabor = array();
            foreach ($pizzaData as $pizza) {
                if ($pizza['sabor'] === $saborBuscado && isset($pizza['ventas'])) {
                    $ventasSabor = $pizza['ventas'];
                }
            }

            // Ahora $ventasSabor contiene las ventas del sabor ingresado.
            echo json_encode($ventasSabor);
            break;

        default:
            echo 'Acción inválida';
            break;
    }
} else {
    echo 'Método no permitido';
}
?>
