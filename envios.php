<?php
// Archivo: envios.php
// Página para que el usuario gestione sus envíos.

include_once 'includes/conexion.php';
check_user_logged_in();

$message = "";
$user_id = $_SESSION['id_usuarios'];

// Lógica para registrar un nuevo envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_item = $_POST['id_item'];
    $direccion = $_POST['direccion_envio'];
    $destinatario = $_POST['nombre_destinatario'];

    // Se conecta a la base de datos
    $conn = connect_to_db();
    
    // Inserta los datos del envío
    $stmt = $conn->prepare("INSERT INTO envios (id_usuario, id_item, direccion_envio, nombre_destinatario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $id_item, $direccion, $destinatario);
    if ($stmt->execute()) {
        $message = "Envío registrado correctamente.";
    } else {
        $message = "Error al registrar el envío: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}

// Obtener la lista de items disponibles
$conn = connect_to_db();
$items_result = $conn->query("SELECT id_item, nombre_item, precio FROM items WHERE stock > 0");
$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}
$conn->close();

// Obtener los envíos del usuario para mostrarlos
$conn = connect_to_db();
$envios_result = $conn->prepare("SELECT e.id_envio, e.direccion_envio, e.nombre_destinatario, e.fecha_envio, i.nombre_item FROM envios e JOIN items i ON e.id_item = i.id_item WHERE e.id_usuario = ? ORDER BY e.fecha_envio DESC");
$envios_result->bind_param("i", $user_id);
$envios_result->execute();
$envios = $envios_result->get_result();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Envíos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Gestionar Envíos</h2>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h3>Registrar un Nuevo Envío</h3>
        <form action="envios.php" method="POST">
            <label for="id_item">Seleccionar Item:</label>
            <select id="id_item" name="id_item" required>
                <option value="">-- Seleccione un item --</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['id_item']); ?>">
                        <?php echo htmlspecialchars($item['nombre_item']) . ' - $' . htmlspecialchars($item['precio']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="direccion_envio">Dirección de Envío:</label>
            <input type="text" id="direccion_envio" name="direccion_envio" required>
            
            <label for="nombre_destinatario">Nombre del Destinatario:</label>
            <input type="text" id="nombre_destinatario" name="nombre_destinatario" required>
            
            <button type="submit">Registrar Envío</button>
        </form>

        <h3 style="margin-top: 40px;">Historial de Envíos</h3>
        <?php if ($envios->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Dirección</th>
                        <th>Destinatario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $envios->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_item']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_envio']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_destinatario']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_envio']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has registrado ningún envío.</p>
        <?php endif; ?>

        <div class="nav-links">
            <a href="panel_control.php">Volver al Panel</a>
        </div>
    </div>
</body>
</html>
