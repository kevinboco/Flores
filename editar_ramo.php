<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: listar_catalogo.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM catalogo_ramos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ramo = $result->fetch_assoc();

if (!$ramo) {
    header('Location: listar_catalogo.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $valor = $_POST['valor'];

    // Si se sube nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = 'uploads/' . $nombreArchivo;

        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            // Opcional: borrar imagen vieja si quieres
            $imagenParaGuardar = $nombreArchivo;
        } else {
            $error = "Error al subir la imagen.";
        }
    } else {
        $imagenParaGuardar = $ramo['imagen']; // mantener imagen vieja
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE catalogo_ramos SET titulo=?, valor=?, imagen=? WHERE id=?");
        $stmt->bind_param("sdsi", $titulo, $valor, $imagenParaGuardar, $id);
        $stmt->execute();

        header('Location: listar_catalogo.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ramo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2 class="mb-4">Editar Ramo</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($ramo['titulo']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Valor</label>
        <input type="number" name="valor" step="0.01" class="form-control" value="<?= $ramo['valor'] ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Imagen Actual</label><br>
        <img src="uploads/<?= htmlspecialchars($ramo['imagen']) ?>" alt="Imagen" width="150">
    </div>
    <div class="mb-3">
        <label class="form-label">Cambiar Imagen (opcional)</label>
        <input type="file" name="imagen" accept="image/*" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="listar_catalogo.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
