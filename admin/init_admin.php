<?php
require_once('../conexion.php');

// Verifica si ya existe un admin
$query = "SELECT * FROM admin";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) > 0) {
    die("Ya existe un usuario administrador. Por seguridad, este script solo puede ejecutarse una vez.");
}

// Credenciales que quieres establecer
$username = "kawhe-admin"; 
$password = "kawhe-pass123"; 

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar el admin en la base de datos
$query = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    echo "Usuario administrador creado exitosamente.<br>";
    echo "Usuario: " . htmlspecialchars($username) . "<br>";
    echo "Por favor, elimina este archivo después de usarlo por seguridad.";
} else {
    echo "Error al crear el usuario administrador: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion); 