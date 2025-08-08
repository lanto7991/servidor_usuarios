<?php
include_once 'includes/conexion.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pass_user = $_POST['pass_user'];

    $stmt = $conn->prepare("SELECT id_usuarios, username, pass_user, perfil FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass_user, $row['pass_user'])) {
            $_SESSION['id_usuarios'] = $row['id_usuarios'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['perfil'] = $row['perfil'];
            
            // Redirige al panel de control sin importar el perfil
            header("Location: panel_control.php");
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "No se encontró el usuario.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Incluye el CSS con el nuevo estilo neón y degradados -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="pass_user">Contraseña:</label>
            <input type="password" id="pass_user" name="pass_user" required>
            
            <button type="submit">Entrar</button>
        </form>
        
        <!-- Aquí está el nuevo enlace para registrarse -->
    </div>
</body>
</html>