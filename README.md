# 📚 Sistema de Gestión de Biblioteca (Mini-App OOP)

Una aplicación web ligera desarrollada en **PHP** implementando el paradigma de **Programación Orientada a Objetos (OOP)** y **MySQL** para la gestión de inventario de libros, registro de usuarios y control de préstamos.

---

## ✨ Características Principales

- **Gestión de Libros:** Crear, leer, actualizar y eliminar (CRUD) registros de libros con control automático de stock.
- **Gestión de Usuarios:** Administración completa del directorio de usuarios de la biblioteca.
- **Control de Préstamos:**
  - Asignación de libros a usuarios.
  - Disminución automática de stock al prestar.
  - Restauración automática de stock al devolver.
- **Arquitectura:** Estructura modular basada en clases para separar la lógica de negocio de la interfaz de usuario.

---

## 🛠️ Requisitos Previos

Para ejecutar este proyecto en tu entorno local, necesitarás tener instalado lo siguiente:

- **XAMPP** (o cualquier entorno similar como Laragon o WAMP) que incluya:
  - Servidor Web Apache.
  - PHP 7.4 o superior.
  - MySQL / MariaDB.
- **Git** (Opcional, para clonar el repositorio).

---

## 🚀 Guía de Instalación Paso a Paso

Sigue estas instrucciones detalladas para desplegar el proyecto en tu máquina local desde cero:

### 1. Preparar el entorno con XAMPP

1. Descarga e instala [XAMPP](https://www.apachefriends.org/es/index.html).
2. Abre el **Panel de Control de XAMPP**.
3. Inicia los servicios de **Apache** y **MySQL** haciendo clic en el botón "Start" junto a cada uno (los módulos deben resaltarse en color verde).

### 2. Obtener y ubicar el Proyecto

1. Dirígete a la carpeta raíz de tu servidor web local. Si instalaste XAMPP con las opciones por defecto en Windows, la ruta exacta es:
   `C:\xampp\htdocs`
2. Abre tu terminal (Símbolo del sistema, PowerShell o Git Bash) en esa ubicación y clona este repositorio ejecutando:
   ```bash
   git clone [https://github.com/WalterCrace/library-management-system.git](https://github.com/WalterCrace/library-management-system)
   ```
