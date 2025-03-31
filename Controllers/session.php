<?php
// Verificar si los datos fueron enviados correctamente
if (isset($_POST['numero_control'], $_POST['nombre'], $_POST['apellido'])) {
    $numero_control = (int) $_POST['numero_control']; // Aseguramos que sea un entero
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    // Aquí puedes hacer lo que necesites con los datos (por ejemplo, guardarlos en una sesión, base de datos, etc.)
    echo "Datos recibidos: <br>";
    echo "Número de control: $numero_control <br>";
    echo "Nombre: $nombre <br>";
    echo "Apellido: $apellido <br>";

    // Si deseas almacenarlos en una variable de sesión
    session_start();
    $_SESSION['numero_control'] = $numero_control;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['apellido'] = $apellido;

    // Puedes redirigir o hacer otras acciones si es necesario
} else {
    echo "No se recibieron los datos correctamente.";
}
?>
