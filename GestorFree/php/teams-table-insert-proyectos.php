<?php
// Cabeceras para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("db.php");  // Conexión a la base de datos

// Leer los datos JSON recibidos
$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->ID_Equipo, $data->Nombre_Equipo, $data->Descripcion, $data->ID_Gestor)) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

// Limpiar datos
$id_equipo = htmlspecialchars(strip_tags($data->ID_Equipo));
$nombre_equipo = htmlspecialchars(strip_tags($data->Nombre_Equipo));
$descripcion = htmlspecialchars(strip_tags($data->Descripcion));
$id_gestor = htmlspecialchars(strip_tags($data->ID_Gestor));

try {
    // Verificar si ya existe el ID_Equipo
    $checkSql = "SELECT COUNT(*) FROM Equipo WHERE ID_Equipo = ?";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$id_equipo]);
    $existe = $stmt->fetchColumn();

    if ($existe) {
        echo json_encode(["error" => "El ID_Equipo ya existe"]);
        exit();
    }

    // Insertar el equipo
    $sql = "INSERT INTO Equipo (ID_Equipo, Nombre_Equipo, Descripcion, ID_Gestor) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_equipo, $nombre_equipo, $descripcion, $id_gestor]);

    echo json_encode(["mensaje" => "✅ Equipo agregado correctamente"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al insertar el equipo: " . $e->getMessage()]);
}
?>
