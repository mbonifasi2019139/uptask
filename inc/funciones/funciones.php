<?php
// Obtiene la pagina actual que se ejecuta
function obtenerPaginaActual()
{
    $archivo = basename($_SERVER['PHP_SELF']);
    $pagina = str_replace(".php", "", $archivo);
    return $pagina;
}

// Obtener los tipos de usuarios
function obtenerTipoUsuario()
{
    include 'conexion.php';

    try {
        //code...
        return $conn->query("SELECT id, nombre FROM tipousuario");
    } catch (Exception $e) {
        //throw $th;
        echo "Error: " + $e->getMessage();
        return false;
    }
}

/** Consultas **/
/** Obtener todos los proyectos **/

function obtenerProyectos()
{
    include 'conexion.php';

    try {

        return $conn->query('SELECT id, nombre FROM proyecto');
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

/** Obtener el nombre del proyecto **/
function obtenerNombreProyecto($id = null)
{
    include 'conexion.php';

    try {

        return $conn->query("SELECT nombre FROM proyecto WHERE id = {$id}");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

// Obtener tareas
function obtenerTareasProyecto($id = null)
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT id, nombre, estado FROM tarea WHERE id_proyecto = {$id}");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

/** Administracion de usuarios **/
function obtenerUsuarios()
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT u.id as id, u.usuario as usuario, tp.nombre as nombre FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

function obtenerUsuario($id_usuario)
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT u.usuario as usuario, tp.nombre as nombre FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario WHERE u.id = {$id_usuario}");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}
