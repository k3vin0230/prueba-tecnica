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
        $stmt = $this->pdo->prepare("
            INSERT INTO user (fullname, email, pass, openid) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$fullname, $email, $pass, $openid]);
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
