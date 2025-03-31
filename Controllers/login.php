<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once "../Models/conexionBD.php";  // Asegúrate de que este archivo define $conexion

$response = ["success" => false, "message" => ""];

$input = json_decode(file_get_contents("php://input"), true); // Leer datos JSON

if (empty($input["numero_control"]) || empty($input["password"])) {
    $response["message"] = "Completa los campos";
} else {
    $usuario = (int) $input["numero_control"]; // Aseguramos que sea un entero
    $clave = $input["password"];

    if (!$conexion) {
        $response["message"] = "Error de conexión a la base de datos";
        echo json_encode($response);
        exit;
    }

    // Usamos sentencias preparadas para mayor seguridad
    $stmt = $conexion->prepare("SELECT numero_control, nombre, apellido FROM Usuario WHERE numero_control = ? AND password = ?");
    $stmt->bind_param("is", $usuario, $clave);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_object()) {
        

        if ($result === FALSE) {
            $response["message"] = "Error al enviar los datos a sesion.php";
        } else {
            $response["success"] = true;
            $response["message"] = "Inicio de sesión exitoso y datos enviados a sesion.php";
        }
    } else {
        $response["message"] = "Número de control o contraseña incorrectos";
    }
    $stmt->close();
}

echo json_encode($response);
?>

