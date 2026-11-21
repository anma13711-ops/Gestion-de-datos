<?php
// conexion.php - Conexión a MySQL usando PDO (XAMPP: usuario root sin contraseña)
function conectar() {
    $host    = "localhost";
    $bd      = "AVS_03_04_01";
    $usuario = "root";
    $clave   = "";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$bd;charset=utf8mb4", $usuario, $clave);
        // Que los errores lancen excepciones
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión a la base de datos.");
    }
}
