<?php
session_start();
require 'db_connection.php'; // Archivo para conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=Por favor, complete ambos campos.");
        exit;
    }

    $sql = "SELECT * FROM usuario WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        header("Location: ../forms/Inicio.php");
        exit;
    } else {
        header("Location: ../index.php?error=Usuario o contraseña incorrectos.");
        exit;
    }
    
}
?>
