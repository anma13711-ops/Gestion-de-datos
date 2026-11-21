<?php
// productoCont.php - CONTROLADOR: recibe los datos de los formularios,
// los valida y decide qué acción ejecutar (insertar, actualizar o borrar).
require_once "Producto.php";

$modelo = new Producto();
$accion = $_POST["accion"] ?? "";

// Redirige a la lista mostrando un mensaje (tipo: ok | error)
function volver($mensaje, $tipo = "ok") {
    header("Location: productoLista.php?msg=" . urlencode($mensaje) . "&tipo=" . $tipo);
    exit;
}

// Sube la foto a la carpeta fotos/. Devuelve el nombre del archivo o null si falla.
function subirFoto($archivo) {
    if (!isset($archivo) || $archivo["error"] !== UPLOAD_ERR_OK) return null;
    $permitidas = ["jpg", "jpeg", "png", "gif", "webp"];
    $ext = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidas)) return null;
    if ($archivo["size"] > 2 * 1024 * 1024) return null;          // máx. 2 MB
    if (@getimagesize($archivo["tmp_name"]) === false) return null; // debe ser imagen real
    if (!is_dir("fotos")) mkdir("fotos", 0777, true);
    $nombre = uniqid("prod_") . "." . $ext;
    return move_uploaded_file($archivo["tmp_name"], "fotos/" . $nombre) ? $nombre : null;
}

// Limpia y valida los campos comunes
function leerDatos() {
    $nombre = trim($_POST["nombre"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $stock  = $_POST["stock"] ?? "";
    $cat    = $_POST["categoria_id"] ?? "";
    if ($nombre === "" || mb_strlen($nombre) > 30 || !is_numeric($precio) || $precio < 0
        || !ctype_digit((string)$stock) || !ctype_digit((string)$cat)) {
        return null;
    }
    return [$nombre, (float)$precio, (int)$stock, (int)$cat];
}

try {
    if ($accion === "insertar") {
        $datos = leerDatos();
        if (!$datos) volver("Datos inválidos. Revisa el formulario.", "error");
        $foto = subirFoto($_FILES["foto"] ?? null);
        if (!$foto) volver("La foto es obligatoria (jpg, png, gif o webp, máx. 2 MB).", "error");
        $modelo->insertar($datos[0], $datos[1], $datos[2], $foto, $datos[3]);
        volver("Producto insertado con éxito.");

    } elseif ($accion === "actualizar") {
        $id = $_POST["id_producto"] ?? "";
        $datos = leerDatos();
        $actual = ctype_digit((string)$id) ? $modelo->obtener($id) : false;
        if (!$datos || !$actual) volver("Datos inválidos. Revisa el formulario.", "error");
        $foto = $actual["foto_p"];                       // conserva la foto anterior
        if (!empty($_FILES["foto"]["name"])) {           // si eligió una nueva, la reemplaza
            $nueva = subirFoto($_FILES["foto"]);
            if (!$nueva) volver("La foto no es válida (jpg, png, gif o webp, máx. 2 MB).", "error");
            @unlink("fotos/" . $foto);
            $foto = $nueva;
        }
        $modelo->actualizar($id, $datos[0], $datos[1], $datos[2], $foto, $datos[3]);
        volver("Producto actualizado con éxito.");

    } elseif ($accion === "borrar") {
        $id = $_POST["id_producto"] ?? "";
        $actual = ctype_digit((string)$id) ? $modelo->obtener($id) : false;
        if (!$actual) volver("El producto no existe.", "error");
        $modelo->borrar($id);
        @unlink("fotos/" . $actual["foto_p"]);           // borra también la imagen
        volver("Producto borrado con éxito.");

    } else {
        volver("Acción no válida.", "error");
    }
} catch (PDOException $e) {
    volver("Ocurrió un error en la base de datos.", "error");
}
