<?php
// productoLista.php - Muestra todos los productos con su foto y los botones Editar / Borrar
require_once "Producto.php";
$productos = (new Producto())->listar();
$msg  = $_GET["msg"]  ?? "";
$tipo = (($_GET["tipo"] ?? "ok") === "error") ? "error" : "ok";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de productos</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <header><h1>Gestión de productos</h1></header>
  <main>
    <h2>Lista de productos</h2>
    <?php if ($msg !== ""): ?>
      <div class="msg <?= $tipo ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <a class="btn" href="productoInsertar.php">+ Nuevo producto</a>
    <a class="btn btn-gris" href="index.php">Menú</a>

    <table>
      <tr>
        <th>ID</th><th>Foto</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Categoría</th><th>Acciones</th>
      </tr>
      <?php if (count($productos) === 0): ?>
        <tr><td colspan="7">No hay productos registrados.</td></tr>
      <?php endif; ?>
      <?php foreach ($productos as $p): ?>
      <tr>
        <td><?= $p["id_producto"] ?></td>
        <td><img src="fotos/<?= htmlspecialchars($p["foto_p"]) ?>" alt="Foto"></td>
        <td><?= htmlspecialchars($p["nombre"]) ?></td>
        <td>$<?= number_format($p["precio"], 2) ?></td>
        <td><?= $p["stock"] ?></td>
        <td><?= htmlspecialchars($p["categoria"]) ?></td>
        <td>
          <a class="btn" href="productoEditar.php?id=<?= $p["id_producto"] ?>">Editar</a>
          <form class="inline" action="productoCont.php" method="post"
                onsubmit="return confirm('¿Seguro que deseas borrar este producto?');">
            <input type="hidden" name="accion" value="borrar">
            <input type="hidden" name="id_producto" value="<?= $p["id_producto"] ?>">
            <button class="btn btn-rojo" type="submit">Borrar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>
