<?php
// Archivo: gestion_items.php
// Página de gestión de items con CRUD completo para el administrador.

include_once 'includes/conexion.php';
check_user_logged_in();

// Verificación de perfil: solo el administrador puede acceder.
if ($_SESSION['perfil'] !== 'administrador') {
    header("Location: panel_control.php");
    exit();
}

$message = "";

// Lógica para añadir o editar un item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre_item'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare("INSERT INTO items (nombre_item, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);
        if ($stmt->execute()) {
            $message = "Item añadido correctamente.";
        } else {
            $message = "Error al añadir el item: " . $conn->error;
        }
        $stmt->close();
    } elseif ($_POST['action'] === 'edit' && isset($_POST['id_item'])) {
        $id_item = $_POST['id_item'];
        $stmt = $conn->prepare("UPDATE items SET nombre_item = ?, descripcion = ?, precio = ?, stock = ? WHERE id_item = ?");
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id_item);
        if ($stmt->execute()) {
            $message = "Item editado correctamente.";
        } else {
            $message = "Error al editar el item: " . $conn->error;
        }
        $stmt->close();
    }
}

// Lógica para eliminar un item
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id_item = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Item eliminado correctamente.";
    } else {
        $message = "Error al eliminar el item: " . $conn->error;
    }
    $stmt->close();
}

// Lógica para obtener todos los items para mostrarlos
$items_result = $conn->query("SELECT id_item, nombre_item, descripcion, precio, stock FROM items ORDER BY id_item DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Items</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Gestión de Items</h2>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h3>Añadir / Editar Item</h3>
        <form action="gestion_items.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id_item" id="edit-id-item">
            
            <label for="nombre_item">Nombre del Item:</label>
            <input type="text" id="nombre_item" name="nombre_item" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required>
            
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            
            <button type="submit" id="submit-button">Añadir Item</button>
        </form>

        <h3 style="margin-top: 40px;">Items Disponibles</h3>
        <?php if ($items_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_item']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_item']); ?></td>
                            <td>$<?php echo htmlspecialchars($row['precio']); ?></td>
                            <td><?php echo htmlspecialchars($row['stock']); ?></td>
                            <td>
                                <a href="gestion_items.php?edit_id=<?php echo htmlspecialchars($row['id_item']); ?>" class="button-action" onclick="loadItemForEdit(<?php echo htmlspecialchars(json_encode($row)); ?>); return false;">Editar</a>
                                <a href="gestion_items.php?delete_id=<?php echo htmlspecialchars($row['id_item']); ?>" class="button-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este item?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay items cargados.</p>
        <?php endif; ?>

        <div class="nav-links">
            <a href="panel_control.php">Volver al Panel</a>
        </div>
    </div>
    
    <script>
    function loadItemForEdit(item) {
        document.getElementById('edit-id-item').value = item.id_item;
        document.getElementById('nombre_item').value = item.nombre_item;
        document.getElementById('descripcion').value = item.descripcion;
        document.getElementById('precio').value = item.precio;
        document.getElementById('stock').value = item.stock;
        document.getElementById('submit-button').innerText = 'Guardar Cambios';
        document.getElementsByName('action')[0].value = 'edit';
    }
    </script>
</body>
</html>