<?php
// Producto.php - MODELO: todas las operaciones con la base de datos.
// Se usan consultas preparadas para evitar inyección SQL.
require_once "conexion.php";

class Producto {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar();
    }

    // INSERTAR un producto nuevo
    public function insertar($nombre, $precio, $stock, $foto, $categoria_id) {
        $sql = "INSERT INTO productos (nombre, precio, stock, foto_p, categoria_id)
                VALUES (:nombre, :precio, :stock, :foto, :categoria)";
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            ":nombre" => $nombre, ":precio" => $precio, ":stock" => $stock,
            ":foto" => $foto, ":categoria" => $categoria_id
        ]);
    }

    // LISTAR todos los productos (con el nombre de su categoría)
    public function listar() {
        $sql = "SELECT p.id_producto, p.nombre, p.precio, p.stock, p.foto_p, c.descripcion AS categoria
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.categoria_id
                ORDER BY p.id_producto";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER un solo producto por su id (para el formulario de actualizar)
    public function obtener($id) {
        $st = $this->pdo->prepare("SELECT * FROM productos WHERE id_producto = :id");
        $st->execute([":id" => $id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR un producto
    public function actualizar($id, $nombre, $precio, $stock, $foto, $categoria_id) {
        $sql = "UPDATE productos
                SET nombre = :nombre, precio = :precio, stock = :stock,
                    foto_p = :foto, categoria_id = :categoria
                WHERE id_producto = :id";
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            ":nombre" => $nombre, ":precio" => $precio, ":stock" => $stock,
            ":foto" => $foto, ":categoria" => $categoria_id, ":id" => $id
        ]);
    }

    // BORRAR un producto
    public function borrar($id) {
        $st = $this->pdo->prepare("DELETE FROM productos WHERE id_producto = :id");
        return $st->execute([":id" => $id]);
    }

    // Lista de categorías para los <select> de los formularios
    public function categorias() {
        return $this->pdo->query("SELECT id_categoria, descripcion FROM categorias ORDER BY descripcion")
                         ->fetchAll(PDO::FETCH_ASSOC);
    }
}
