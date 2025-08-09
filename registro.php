<?php
// Archivo: registro.php
// Este archivo contiene el formulario y el script para registrar nuevos usuarios.
// El campo "perfil" debe ser un nuevo campo en la tabla "usuarios".
include_once 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $perfil = $_POST['perfil'];
    $pass_user = $_POST['pass_user'];

    // Encriptar la contraseña de forma segura
    $pass_user_hash = password_hash($pass_user, PASSWORD_DEFAULT);

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (username, pass_user, perfil) VALUES (?, ?, ?)");
    // El campo "perfil" es necesario para que esta lógica funcione.
    $stmt->bind_param("sss", $username, $pass_user_hash, $perfil);

    if ($stmt->execute()) {
        echo "Nuevo usuario registrado exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Registro de Usuarios</title>
</head>
<body>
    <h2>Registro de Nuevo Usuario</h2>
    <form action="registro.php" method="POST">
        <label for="username">Nombre de Usuario:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="pass_user">Contraseña:</label><br>
        <input type="password" id="pass_user" name="pass_user" required><br><br>
        
        <label for="perfil">Perfil:</label><br>
        <select id="perfil" name="perfil">
            <option value="usuario">Usuario</option>
            <option value="administrador">Administrador</option>
        </select><br><br>
        
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
