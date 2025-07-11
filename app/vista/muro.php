<?php


$comentariosJson = file_get_contents('http://localhost/prueba-backend/public/index.php?controller=comment&action=ver_muro');
$comentarios = json_decode($comentariosJson, true);
if (!is_array($comentarios)) {
    $comentarios = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Muro de comentarios</title>
    <link rel="stylesheet" href="estilos/muro.css">
</head>
<body>

<h2>Bienvenido</h2>


<h3>Escribir un nuevo comentario</h3>
<form id="form-comentario">
    <textarea name="comentario" rows="3" cols="50" placeholder="Escribe tu comentario aquí..." required></textarea><br>
    <button type="submit">Publicar</button>
</form>
<hr>


<script>
// Enviar nuevo comentario
document.getElementById('form-comentario').addEventListener('submit', async function(e) {
    e.preventDefault();
    const texto = this.comentario.value.trim();
    if (texto === '') return;

    const res = await fetch('../../public/index.php?controller=comment&action=crear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            user_id: 4,
            comment: texto
        })
    });

    const data = await res.json();

    if (data.message) {
        alert('Comentario publicado');
        location.reload();
    } else {
        alert(data.error || 'Error al publicar');
    }
});
</script>











<h3>Muro de comentarios:</h3>

<?php foreach ($comentarios as $comentario): ?>
    <div class="comentario" data-id="<?= $comentario['comment_id'] ?>">
        <strong><?= htmlspecialchars($comentario['fullname']) ?></strong><br>
        <p><?= htmlspecialchars($comentario['coment_text']) ?></p>
        <span class="likes">❤️ <span class="like-count"><?= $comentario['likes'] ?></span> likes</span><br>
        <button class="like-btn">Dar like</button>
    </div>
<?php endforeach; ?>

<script>
document.querySelectorAll('.like-btn').forEach(boton => {
    boton.addEventListener('click', async function () {
        const div = this.closest('.comentario');
        const commentId = div.dataset.id;

        const res = await fetch('../../public/index.php?controller=comment&action=like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment_id: commentId })
        });

        const data = await res.json();

        if (data.message) {
            const likeCount = div.querySelector('.like-count');
            likeCount.textContent = parseInt(likeCount.textContent) + 1;
        } else {
            alert(data.error || 'Error al dar like');
        }
    });
});
</script>

</body>
</html>
