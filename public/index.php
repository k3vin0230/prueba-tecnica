<?php
// Conexión a la base de datos
require_once '../db/conexion.php';

// Controladores
require_once '../app/controladores/UsuarioControlador.php';
require_once '../app/controladores/ComentarioControlador.php';

// CORS y tipo de respuesta
header("Access-Control-Allow-Origin: *"); //"*" es para todos los puertos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Obtener parámetros desde la URL
$controlador = $_GET['controller'] ?? '';
$accion = $_GET['action'] ?? '';

// Enrutamiento al controlador correspondiente
switch ($controlador) {
    case 'user':
        $instanciaControlador = new UserController($pdo);
        break;
    case 'comment':
        require_once __DIR__ . '/../app/controladores/ComentarioControlador.php';
         $instanciaControlador = new CommentController($pdo);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Controlador no encontrado']);
        exit;
}

// Ejecutar la acción si existe en el controlador
if (method_exists($instanciaControlador, $accion)) {
    $instanciaControlador->$accion();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Acción "' . $accion . '" no encontrada']);
}
