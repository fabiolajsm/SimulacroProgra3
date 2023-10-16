<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'consultarPizza':
            include('PizzaConsultar.php');
            break;
        case 'altaVenta':
            include('AltaVenta.php');
            break;
        default:
            echo 'Acción no válida';
    }
} else {
    echo 'Método no permitido';
}
?>
