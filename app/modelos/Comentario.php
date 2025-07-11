<?php
// Modelo para comentarios
class UserComment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function crear($user_id, $comment)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO user_comment (user, coment_text)
                VALUES (?, ?)"
        );
        return $stmt->execute([$user_id, $comment]);
    }


    // Obtener total de likes por usuario, solo si tiene comentarios
    public function getLikesPorUsuario()
    {
        $sql = "
            SELECT 
                u.id,
                u.fullname,
                u.email,
                COUNT(c.id) AS total_comentarios,
                SUM(c.likes) AS total_likes
            FROM user u
            INNER JOIN user_comment c ON u.id = c.user
            GROUP BY u.id, u.fullname, u.email
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los comentarios con datos del usuario
public function obtenerComentariosConUsuarios()
{
    $stmt = $this->pdo->query("
        SELECT 
            c.id AS comment_id,
            u.fullname,
            c.coment_text,
            c.likes
        FROM user_comment c
        JOIN user u ON c.user = u.id
        ORDER BY c.creation_date DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Sumar 1 like a un comentario
public function darLike($commentId)
{
    $stmt = $this->pdo->prepare("
        UPDATE user_comment 
        SET likes = likes + 1 
        WHERE id = ?
    ");
    return $stmt->execute([$commentId]);
}


}
