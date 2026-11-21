<?php
// productoEditar.php - Formulario para actualizar: muestra los datos del producto seleccionado
require_once "Producto.php";
$modelo = new Producto();
$id = $_GET["id"] ?? "";
$p = ctype_digit((string)$id) ? $modelo->obtener($id) : false;
if (!$p) { header("Location: productoLista.php?msg=" . urlencode("El producto no existe.") . "&tipo=error"); exit; }
$categorias = $modelo->categorias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizar producto</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <header><h1>Gestión de productos</h1></header>
  <main>
    <h2>Actualizar producto #<?= $p["id_producto"] ?></h2>
    <form action="productoCont.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="actualizar">
      <input type="hidden" name="id_producto" value="<?= $p["id_producto"] ?>">

      <label>Nombre</label>
      <input type="text" name="nombre" maxlength="30" value="<?= htmlspecialchars($p["nombre"]) ?>" required>

      <label>Precio</label>
      <input type="number" name="precio" step="0.01" min="0" value="<?= $p["precio"] ?>" required>

      <label>Stock</label>
      <input type="number" name="stock" min="0" value="<?= $p["stock"] ?>" required>

      <label>Categoría</label>
      <select name="categoria_id" required>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= $c["id_categoria"] ?>" <?= $c["id_categoria"] == $p["categoria_id"] ? "selected" : "" ?>>
            <?= htmlspecialchars($c["descripcion"]) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Foto actual</label>
      <img src="fotos/<?= htmlspecialchars($p["foto_p"]) ?>" alt="Foto actual" width="120">

      <label>Cambiar foto (opcional)</label>
      <input type="file" name="foto" accept="image/*">

      <p>
        <button class="btn" type="submit">Actualizar</button>
        <a class="btn btn-gris" href="productoLista.php">Cancelar</a>
      </p>
    </form>
  </main>
</body>
</html>
