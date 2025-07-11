<?php
require_once __DIR__ . '/../modelos/Comentario.php';

class CommentController
{
    private $commentModel;

    public function __construct($pdo)
    {
        $this->commentModel = new UserComment($pdo);
    }

    // Crear un comentario nuevo
    public function crear()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['user_id'], $data['comment'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan datos']);
            return;
        }

        if ($this->commentModel->crear($data['user_id'], $data['comment'])) {
            echo json_encode(['message' => 'Comentario creado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear comentario']);
        }
    }

    // Mostrar todos los comentarios con usuario
    public function ver_muro()
    {
        header('Content-Type: application/json');
        $comentarios = $this->commentModel->obtenerComentariosConUsuarios();
        echo json_encode($comentarios);
    }

    // Dar like a un comentario
    public function like()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['comment_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta comment_id']);
            return;
        }

        if ($this->commentModel->darLike($data['comment_id'])) {
            echo json_encode(['message' => 'Like registrado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al dar like']);
        }
    }





}
