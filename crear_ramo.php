<?php
ob_start();
include 'conexion.php';
include 'texto circular.php';
include 'nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $valor = $_POST['valor'];
    $descripcion = $_POST['description'];

    // Validar imagen subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $categoria = $_POST['categoria']; // Nuevo campo
        $rutaDestino = 'uploads/' . $nombreArchivo;

        // Mover archivo a la carpeta uploads
        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            $stmt = $conn->prepare("INSERT INTO catalogo_ramos (titulo, valor, imagen, description, categoria) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $titulo, $valor, $nombreArchivo, $descripcion, $categoria);
            $stmt->execute();
        } else {
            $error = "Error al subir la imagen.";
        }
    } else {
        $error = "Por favor seleccione una imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Ramo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('uploads/fondo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="container py-4">

<h2 class="mb-4">Agregar Nuevo Ramo</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Valor</label>
        <input type="number" name="valor" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Categoría</label>
        <input type="text" name="categoria" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" accept="image/*,video/*" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="listar_catalogo.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>

