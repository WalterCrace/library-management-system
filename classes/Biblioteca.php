<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro) {
        $query = "INSERT INTO libros (titulo, autor, isbn, cantidad) VALUES (:titulo, :autor, :isbn, :cantidad)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindValue(':titulo', $libro->getTitulo());
        $stmt->bindValue(':autor', $libro->getAutor());
        $stmt->bindValue(':isbn', $libro->getIsbn());
        $stmt->bindValue(':cantidad', $libro->getCantidad());
        
        return $stmt->execute();
    }

    public function editarLibro($id, $nuevosDatos) {
       try {
            $query = "UPDATE libros SET titulo = :titulo, autor = :autor, isbn = :isbn, cantidad = :cantidad WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindValue(':titulo', $nuevosDatos['titulo']);
            $stmt->bindValue(':autor', $nuevosDatos['autor']);
            $stmt->bindValue(':isbn', $nuevosDatos['isbn']);
            $stmt->bindValue(':cantidad', $nuevosDatos['cantidad'], PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // AQUÍ ESTÁ EL CAMBIO: Hacemos que detenga todo y nos muestre el error de SQL
            die("Error SQL al editar: " . $e->getMessage()); 
        }
    }

    public function eliminarLibro($id) {
        try {
            $query = "DELETE FROM libros WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejar error (por ejemplo, si el libro está asociado a un préstamo activo)
            return false;
        }
    }

    public function obtenerLibros() {
        $query = "SELECT * FROM libros";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarLibro($id) {
        $query = "SELECT * FROM libros WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario) {
        // TODO: Insertar usuario en base de datos
    }

    public function editarUsuario($id, $nuevosDatos) {
        // TODO: Actualizar usuario en base de datos
    }

    public function eliminarUsuario($id) {
        // TODO: Eliminar usuario de base de datos
    }

    public function obtenerUsuarios() {
        // TODO: Retornar lista de usuarios
        return [];
    }

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id) {
        try {
            $this->conn->beginTransaction();

            // Insertar préstamo
            $queryPrestamo = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) VALUES (:libro_id, :usuario_id, CURDATE(), 'activo')";
            $stmtPrestamo = $this->conn->prepare($queryPrestamo);
            $stmtPrestamo->execute([':libro_id' => $libro_id, ':usuario_id' => $usuario_id]);

            //Disminuir stock
            $queryStock = "UPDATE libros SET cantidad = cantidad - 1 WHERE id = :libro_id AND cantidad > 0";
            $stmtStock = $this->conn->prepare($queryStock);
            $stmtStock->execute([':libro_id' => $libro_id]);

            if($stmtStock->rowCount() == 0) {
                throw new Exception("No hay stock disponible.");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function devolverLibro($prestamo_id) {
        try {
            $this->conn->beginTransaction();

            // Obtener ID del libro
            $queryGetLibro = "SELECT libro_id FROM prestamos WHERE id = :prestamo_id AND estado = 'activo'";
            $stmtGet = $this->conn->prepare($queryGetLibro);
            $stmtGet->execute([':prestamo_id' => $prestamo_id]);
            $prestamo = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if($prestamo) {
                // Actualizar préstamo
                $queryUpdate = "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = CURDATE() WHERE id = :prestamo_id";
                $this->conn->prepare($queryUpdate)->execute([':prestamo_id' => $prestamo_id]);

                //Aumentar stock
                $queryStock = "UPDATE libros SET cantidad = cantidad + 1 WHERE id = :libro_id";
                $this->conn->prepare($queryStock)->execute([':libro_id' => $prestamo['libro_id']]);

                $this->conn->commit();
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function obtenerPrestamosActivos() {
        // TODO: Retornar lista de préstamos activos
        return [];
    }
}
