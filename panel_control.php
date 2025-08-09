<?php
// Archivo: panel_control.php
include_once 'includes/conexion.php';
// Verifica si el usuario ha iniciado sesión

check_user_logged_in();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <h3>Panel de Control</h3>
        
        <div class="nav-links">
            <?php if ($_SESSION['perfil'] == 'administrador'): ?>
                <a href="admin.php">Gestión de Usuarios</a>
                <a href="clientes.php">Gestión de Clientes</a>
            <?php endif; ?>
            <a href="perfil.php">Ver mi Perfil</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>