<?php

class Prestamo {
    private $id;
    private $libro_id;
    private $usuario_id;
    private $fecha_prestamo;
    private $fecha_devolucion;
    private $estado;

    public function __construct($libro_id = null, $usuario_id = null) {
        $this->libro_id = $libro_id;
        $this->usuario_id = $usuario_id;
        $this->fecha_prestamo = date('y-m-d');
        $this->estado = 'activo';
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getLibroId() {
        return $this->libro_id;
    }

    public function setLibroId($libro_id) {
        $this->libro_id = $libro_id;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function getFechaPrestamo() {
        return $this->fecha_prestamo;
    }

    public function setFechaPrestamo($fecha_prestamo){
        $this->fecha_prestamo = $fecha_prestamo;
    }

    public function getFechaDevolucion() {
        return $this->fecha_devolucion;
    }

    public function setFechaDevolucion($fecha_devolucion) {
        $this->fecha_devolucion = $fecha_devolucion;
    }

    public function getEstado() {
        return $this->estado = $estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }
}
