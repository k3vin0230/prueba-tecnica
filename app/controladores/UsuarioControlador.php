<?php
require_once __DIR__ . '/../modelos/Usuario.php';

class UserController
{
    private $userModel;
    private $pdo; 

    public function __construct($pdo)
    {
        $this->pdo = $pdo; 
        $this->userModel = new User($pdo);
    }

    // Crear nuevo usuario
    public function crear()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // Validar datos mínimos
        if (!isset($data['fullname'], $data['email'], $data['pass'], $data['openid'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta Nombre, Correo o Contraseña']);
            return;
        }


        if ($this->userModel->create($data['fullname'], $data['email'], $data['pass'], $data['openid'])) {
            echo json_encode(['message' => 'Usuario creado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el usuario']);
        }
    }

    // Obtener todos los usuarios o uno por ID
    public function ver()
    {
        header('Content-Type: application/json');

        if (isset($_GET['id'])) {
            $user = $this->userModel->getById($_GET['id']);
            echo json_encode($user ?: ['error' => 'Usuario no encontrado']);
        } else {
            $users = $this->userModel->getAll();
            echo json_encode($users);
        }
    }

    // Actualizar usuario
    public function actualizar()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'], $data['fullname'], $data['email'], $data['pass'], $data['openid'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan datos para actualizar']);
            return;
        }

        if ($this->userModel->update($data['id'], $data['fullname'], $data['email'], $data['pass'], $data['openid'])) {
            echo json_encode(['message' => 'Usuario actualizado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar usuario']);
        }
    }

    // Eliminar usuario
    public function eliminar()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No se proporcionó ID']);
            return;
        }

        if ($this->userModel->delete($data['id'])) {
            echo json_encode(['message' => 'Usuario eliminado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar usuario']);
        }
    }

        public function con_likes()
    {
        require_once __DIR__ . '/../modelos/Comentario.php';
        $commentModel = new UserComment($this->pdo);

        header('Content-Type: application/json');
        $usuarios = $commentModel->getLikesPorUsuario();
        echo json_encode($usuarios);
    }

    
}
