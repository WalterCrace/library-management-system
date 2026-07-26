<?php
require_once 'classes/Biblioteca.php';

// TODO: Instanciar la clase Biblioteca
$biblioteca = new Biblioteca();

// Lógica de enrutamiento
$action = isset($_GET['action']) ? $_GET['action'] : 'libros';

// Procesar formulario de nuevo libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_libro'])) {
    $nuevoLibro = new Libro($_POST['titulo'], $_POST['autor'], $_POST['isbn'], $_POST['cantidad']);
    $biblioteca->agregarLibro($nuevoLibro);
    header("Location: index.php");
    exit();
}

// Procesar eliminación de libro
if ($action === 'eliminar_libro' && isset($_GET['id'])) {
    $biblioteca->eliminarLibro($_GET['id']);
    // Redirigir para limpiar la URL
    header("Location: index.php?action=libros");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_libro'])) {
    $id = $_POST['id'];
    
    $nuevosDatos = [
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'isbn' => $_POST['isbn'],
        'cantidad' => $_POST['cantidad']
    ];
    
    // Guardamos el resultado en una variable
    $resultado = $biblioteca->editarLibro($id, $nuevosDatos);
    
    if ($resultado) {
        // Si funcionó, redirige
        header("Location: index.php?action=libros");
        exit();
    } else {
        // Si no funcionó, muestra este mensaje
        die("Ocurrió un error desconocido al intentar actualizar el libro.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        /* TODO: Agregar estilos CSS */
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; background: #eee; padding: 10px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; font-weight: bold; }
        .container { max-width: 900px; margin: 0 auto; }
        table {width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td {border: 1px solid #ccc; padding: 10px: text-aling: left;}
        .form-group {margin-bottom: 10px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Biblioteca Mini-App</h1>
        
        <nav>
            <a href="index.php">Inicio / Libros</a>
            <a href="index.php?action=usuarios">Usuarios</a>
            <a href="index.php?action=prestamos">Préstamos</a>
        </nav>

        <div id="content">
          <?php if ($action === 'libros'): ?>
                <h2>Gestión de Libros</h2>
                
                <!-- Formulario Agregar Libro -->
                <form method="POST" style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd;">
                    <h3>Agregar Nuevo Libro</h3>
                    <div class="form-group"><input type="text" name="titulo" placeholder="Título" required></div>
                    <div class="form-group"><input type="text" name="autor" placeholder="Autor" required></div>
                    <div class="form-group"><input type="text" name="isbn" placeholder="ISBN" required></div>
                    <div class="form-group"><input type="number" name="cantidad" placeholder="Cantidad" required></div>
                    <button type="submit" name="crear_libro">Guardar Libro</button>
                </form>

                <!-- Listado de Libros -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $libros = $biblioteca->obtenerLibros();
                        foreach($libros as $libro): 
                        ?>
                        <tr>
                            <td><?= $libro['id'] ?></td>
                            <td><?= $libro['titulo'] ?></td>
                            <td><?= $libro['autor'] ?></td>
                            <td><?= $libro['isbn'] ?></td>
                            <td><?= $libro['cantidad'] ?></td>
                            <td>
                                <a href="index.php?action=editar_libro&id=<?= $libro['id'] ?>" style="color: blue;">Editar</a> |
                                <a href="index.php?action=eliminar_libro&id=<?= $libro['id'] ?>" style="color: red;" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
               
            <?php if ($action === 'editar_libro' && isset($_GET['id'])): 
                // Buscamos el libro actual
                $libroActual = $biblioteca->buscarLibro($_GET['id']);
                
                if (!$libroActual): 
                    echo "<p>Error: El libro no existe.</p>";
                else:
            ?>
                <h2>Editar Libro</h2>
                <form method="POST" action="index.php" style="background: #e9ecef; padding: 15px; border: 1px solid #ddd;">
                    
                    <input type="hidden" name="id" value="<?= $libroActual['id'] ?>">
                    
                    <div class="form-group">
                        <label>Título:</label><br>
                        <input type="text" name="titulo" value="<?= $libroActual['titulo'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Autor:</label><br>
                        <input type="text" name="autor" value="<?= $libroActual['autor'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>ISBN:</label><br>
                        <input type="text" name="isbn" value="<?= $libroActual['isbn'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Cantidad:</label><br>
                        <input type="number" name="cantidad" value="<?= $libroActual['cantidad'] ?>" required>
                    </div>
                    
                    <button type="submit" name="actualizar_libro">Guardar Cambios</button>
                    <a href="index.php?action=libros" style="margin-left: 15px; text-decoration: none; color: red;">Cancelar</a>
                </form>
            <?php 
                endif; 
            endif;
            ?>
        </div>
    </div>
</body>
</html>
