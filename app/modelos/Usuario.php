<?php
// Modela de la tabla user
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear usuario 
    public function create($fullname, $email, $pass, $openid)
    {
        try{
            $stmt = $this->pdo->prepare("
            INSERT INTO user (fullname, email, pass, openid) 
            VALUES (?, ?, ?, ?)"
            );
            return $stmt->execute([$fullname, $email, $pass, $openid]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Error de integridad (duplicado)
                $msg = $e->getMessage();

                if (str_contains($msg, 'email')) {
                    throw new Exception('El correo electrónico ya está registrado.');
                } elseif (str_contains($msg, 'openid')) {
                    throw new Exception('El usuario ya ha iniciado sesión con Google anteriormente.');
                } else {
                    throw new Exception('Dato duplicado no permitido.');
                }
            }

            // Otro error de base de datos
            throw new Exception('Error al crear el usuario: ' . $e->getMessage());
        }
        
    }

    // Obtener todos los usuarios
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener usuario por ID
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario
    public function update($id, $fullname, $email, $pass, $openid)
    {
        $stmt = $this->pdo->prepare("
            UPDATE user 
            SET fullname = ?, email = ?, pass = ?, openid = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$fullname, $email, $pass, $openid, $id]);
    }

    // Eliminar usuario
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
