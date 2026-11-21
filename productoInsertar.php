<?php
// productoInsertar.php - Formulario para insertar un producto nuevo
require_once "Producto.php";
$categorias = (new Producto())->categorias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Insertar producto</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <header><h1>Gestión de productos</h1></header>
  <main>
    <h2>Insertar producto</h2>
    <form action="productoCont.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="insertar">

      <label>Nombre</label>
      <input type="text" name="nombre" maxlength="30" required>

      <label>Precio</label>
      <input type="number" name="precio" step="0.01" min="0" required>

      <label>Stock</label>
      <input type="number" name="stock" min="0" required>

      <label>Categoría</label>
      <select name="categoria_id" required>
        <option value="">-- Selecciona --</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['descripcion']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Foto del producto</label>
      <input type="file" name="foto" accept="image/*" required>

      <p>
        <button class="btn" type="submit">Guardar</button>
        <a class="btn btn-gris" href="index.php">Menú</a>
      </p>
    </form>
  </main>
</body>
</html>
