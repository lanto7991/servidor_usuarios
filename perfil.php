<?php
// Archivo: perfil.php
// Esta página muestra los detalles del perfil del usuario con un diseño similar a un perfil público.

// Se incluye el archivo de conexión.
include_once 'includes/conexion.php';

// Se verifica si el usuario ha iniciado sesión. Si no, lo redirige al login.
check_user_logged_in();

// Se obtienen los datos del usuario de la sesión activa.
$id_usuario = $_SESSION['id_usuarios'];
$username = $_SESSION['username'];
$perfil = $_SESSION['perfil'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <!-- Se enlaza el archivo CSS para mantener el estilo de toda la aplicación -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container profile-container">
        <!-- Sección de encabezado del perfil con imagen de portada -->
        <div class="profile-header">
            <div class="cover-photo" style="background-image: url('https://placehold.co/800x250/0d0d1a/a3a3a3?text=Portada+del+Perfil');"></div>
            <div class="profile-info">
                <img src="https://placehold.co/150x150/00f0ff/1a1a2e?text=Foto" alt="Foto de Perfil" class="profile-pic">
                <div class="profile-details">
                    <!-- Muestra el nombre de usuario y el perfil -->
                    <h2><?php echo htmlspecialchars($username); ?></h2>
                    <span class="profile-badge"><?php echo htmlspecialchars(ucfirst($perfil)); ?></span>
                </div>
            </div>
        </div>

        <!-- Contenido principal del perfil (bio, posts, etc.) -->
        <div class="profile-content">
            <h3>Acerca de mí</h3>
            <p class="profile-bio">
                ¡Hola! Soy un <?php echo htmlspecialchars($perfil); ?> de la plataforma TenTen.
                Estoy aquí para explorar y conectar con otros usuarios.
                ¡Gracias por visitar mi perfil!
            </p>
            <div class="nav-links">
                <a href="panel_control.php">Volver al Panel</a>
            </div>
        </div>
    </div>
</body>
</html>