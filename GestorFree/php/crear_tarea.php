<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Si es una solicitud OPTIONS (preflight), terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'gp_base';
$username = 'root';
$password = '';

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Primero, obtener el máximo ID actual
    $stmt = $pdo->query("SELECT MAX(id_tarea) as max_id FROM tarea");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextId = ($result['max_id'] ?? 0) + 1;
    
    // Preparar la consulta SQL incluyendo el id_tarea
    $sql = "INSERT INTO tarea (id_tarea, nombre_tarea, descripcion, fecha_entrega, hora_entrega, 
            prioridad, id_usuario, id_estado, id_materia, id_proyecto) 
            VALUES (:id_tarea, :nombre_tarea, :descripcion, :fecha_entrega, :hora_entrega, 
            :prioridad, :id_usuario, :id_estado, :id_materia, :id_proyecto)";
    
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la consulta con los datos recibidos
    $result = $stmt->execute([
        ':id_tarea' => $nextId,
        ':nombre_tarea' => $data['nombre_tarea'],
        ':descripcion' => $data['descripcion'],
        ':fecha_entrega' => $data['fecha_entrega'],
        ':hora_entrega' => $data['hora_entrega'],
        ':prioridad' => $data['prioridad'],
        ':id_usuario' => $data['id_usuario'],
        ':id_estado' => $data['id_estado'],
        ':id_materia' => $data['id_materia'],
        ':id_proyecto' => $data['id_proyecto']
    ]);
    
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tarea creada exitosamente',
            'id' => $nextId
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al crear la tarea'
        ]);
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
    ]);
}
?>
