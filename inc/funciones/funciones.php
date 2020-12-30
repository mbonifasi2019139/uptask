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
        return $conn->query("SELECT t.id AS id, t.nombre AS nombre, t.estado AS estado, u.usuario AS usuario FROM tarea t INNER JOIN usuario u ON u.id = t.id_usuario WHERE id_proyecto = {$id}");
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

function obtenerUsuarioDelHas($id_proyecto)
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT phu.id as idPhu, u.usuario as usuario, tp.nombre as nombre, u.id as id FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario INNER JOIN proyecto_has_usuario phu on phu.id_usuario = u.id INNER JOIN proyecto p ON phu.id_proyecto = p.id WHERE p.id = {$id_proyecto}");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

function obtenerUsuarioProyectoHas($id_usuario, $id_proyecto)
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT u.usuario as usuario, tp.nombre as nombre, u.id as id FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario INNER JOIN proyecto_has_usuario phu on phu.id_usuario = u.id INNER JOIN proyecto p ON phu.id_proyecto = p.id WHERE p.id = {$id_proyecto} AND u.id = {$id_usuario}");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

function obtenerUsuarioNoRegistrados($id_proyecto)
{
    include 'conexion.php';

    try {
        return $conn->query("SELECT u.id AS id, u.usuario AS usuario, tp.id AS idTipo, tp.nombre AS tpNombre FROM usuario u 
        INNER JOIN tipousuario tp ON tp.id = u.idtipousuario WHERE u.id NOT IN 
        (SELECT u.id
        FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario INNER JOIN proyecto_has_usuario phu on phu.id_usuario = u.id INNER JOIN proyecto p ON phu.id_proyecto = p.id
        WHERE p.id =  {$id_proyecto})");
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
        return false;
    }
}

/*
SELECT u.id, u.usuario, tp.id, tp.nombre FROM usuario u 
INNER JOIN tipousuario tp ON tp.id = u.idtipousuario WHERE u.id NOT IN 
(SELECT u.id
FROM usuario u INNER JOIN tipousuario tp ON tp.id = u.idtipousuario INNER JOIN proyecto_has_usuario phu on phu.id_usuario = u.id INNER JOIN proyecto p ON phu.id_proyecto = p.id
WHERE p.id = 1 )
*/