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
// Precesar editar libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_libro'])) {
    $id = $_POST['id'];
    
    $nuevosDatos = [
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'isbn' => $_POST['isbn'],
        'cantidad' => $_POST['cantidad']
    ];
    
    $resultado = $biblioteca->editarLibro($id, $nuevosDatos);
    
    if ($resultado) {
        header("Location: index.php?action=libros");
        exit();
    } else {
        die("Ocurrió un error desconocido al intentar actualizar el libro.");
    }
}

// Procesar crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_usuario'])) {
    $nuevoUsuario = new Usuario($_POST['nombre'], $_POST['email'], $_POST['telefono']);
    $biblioteca->agregarUsuario($nuevoUsuario);
    
    header("Location: index.php?action=usuarios");
    exit();
}

// Procesar editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $id = $_POST['id'];
    
    $nuevosDatos = [
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'telefono' => $_POST['telefono']
    ];
    
    $resultado = $biblioteca->editarUsuario($id, $nuevosDatos);
    
    if ($resultado) {
        header("Location: index.php?action=usuarios");
        exit();
    } else {
        die("Error desconocido al intentar actualizar el usuario. Verifica que el correo no esté duplicado.");
    }
}

//Procesar eliminar usuario
if ($action === 'eliminar_usuario' && isset($_GET['id'])) {
    $biblioteca->eliminarUsuario($_GET['id']);
    header("Location: index.php?action=usuarios");
    exit();
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
                        <?php endforeach; 
                            ?>
                    </tbody>
                </table>
            <?php endif; 
                ?>
               
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
            <!-- Vista de usuario -->
            <?php if ($action === 'usuarios'): ?>
                <h2>Gestión de Usuarios</h2>
                
                <!-- Formulario Agregar Usuario -->
                <form method="POST" action="index.php" style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd;">
                    <h3>Agregar Nuevo Usuario</h3>
                    <div class="form-group"><input type="text" name="nombre" placeholder="Nombre completo" required></div>
                    <div class="form-group"><input type="email" name="email" placeholder="Correo electrónico" required></div>
                    <div class="form-group"><input type="text" name="telefono" placeholder="Teléfono" required></div>
                    <button type="submit" name="crear_usuario">Guardar Usuario</button>
                </form>

                <!-- Listado de Usuarios -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $usuarios = $biblioteca->obtenerUsuarios();
                        foreach($usuarios as $usuario): 
                        ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= $usuario['nombre'] ?></td>
                            <td><?= $usuario['email'] ?></td>
                            <td><?= $usuario['telefono'] ?></td>
                            <td>
                                <a href="index.php?action=editar_usuario&id=<?= $usuario['id'] ?>" style="color: blue;">Editar</a> | 
                                <a href="index.php?action=eliminar_usuario&id=<?= $usuario['id'] ?>" style="color: red;" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>


            <?php if ($action === 'editar_usuario' && isset($_GET['id'])): 
                $usuarioActual = $biblioteca->buscarUsuario($_GET['id']);
                
                if (!$usuarioActual): 
                    echo "<p>Error: El usuario no existe.</p>";
                else:
            ?>
                <h2>Editar Usuario</h2>
                <form method="POST" action="index.php" style="background: #e9ecef; padding: 15px; border: 1px solid #ddd;">
                    
                    <input type="hidden" name="id" value="<?= $usuarioActual['id'] ?>">
                    
                    <div class="form-group">
                        <label>Nombre:</label><br>
                        <input type="text" name="nombre" value="<?= $usuarioActual['nombre'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label><br>
                        <input type="email" name="email" value="<?= $usuarioActual['email'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono:</label><br>
                        <input type="text" name="telefono" value="<?= $usuarioActual['telefono'] ?>" required>
                    </div>
                    
                    <button type="submit" name="actualizar_usuario">Guardar Cambios</button>
                    <a href="index.php?action=usuarios" style="margin-left: 15px; text-decoration: none; color: red;">Cancelar</a>
                </form>
            <?php 
                endif; 
            endif; 
            ?>
        </div>
    </div>
</body>
</html>
