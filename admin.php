<?php
// Archivo: admin.php
// Panel de administración. Solo accesible para perfiles de 'administrador'.
include_once 'includes/conexion.php';
check_admin_access();

// Obtener todos los usuarios de la tabla "usuarios"
$sql_usuarios = "SELECT id_usuarios, username, perfil FROM usuarios";
$result_usuarios = $conn->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
    <link rel="stylesheet" href="css/style.css">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>
    <h2>Bienvenido Administrador</h2>
    
    <h3>Gestión de Usuarios</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Perfil</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result_usuarios->num_rows > 0) {
            while($row = $result_usuarios->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_usuarios"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["perfil"] . "</td>";
                echo "<td>";
                echo "<a href='editar_usuario.php?id=" . $row["id_usuarios"] . "'>Editar</a> | ";
                echo "<a href='eliminar_usuario.php?id=" . $row["id_usuarios"] . "'>Eliminar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay usuarios registrados</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="registro.php">Registrar Nuevo Usuario</a> |
    <a href="clientes.php">Gestionar Clientes</a> |
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>