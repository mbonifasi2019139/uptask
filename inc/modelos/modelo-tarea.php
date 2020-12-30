<?php

$accion = $_POST['accion'];
$id_proyecto = (int) $_POST['id_proyecto'];
$tarea = $_POST['tarea'];
$estado = $_POST['estado'];
$id_tarea = $_POST['id'];
$id_usuario = (int) $_POST['id_usuario'];

if ($accion === 'crear') {
    // importar la conexion
    include '../funciones/conexion.php';

    try {
        // Realizar la consulta a la base de datos
        $stmt = $conn->prepare('INSERT INTO tarea (nombre, id_proyecto, id_usuario) values (?, ?, ?)');
        $stmt->bind_param('sii', $tarea, $id_proyecto, $id_usuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'id_insertado' => $stmt->insert_id,
                'tipo' => $accion,
                'tarea' => $tarea,
                'id_usuario' => $id_usuario
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // En caso de error, tomar la exception
        $respuesta = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}

if ($accion === 'actualizar') {
    // importar la conexion
    include '../funciones/conexion.php';

    try {
        // Realizar la consulta a la base de datos
        $stmt = $conn->prepare('UPDATE tarea SET estado = ? WHERE id = ?');
        $stmt->bind_param('ii', $estado, $id_tarea);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // En caso de error, tomar la exception
        $respuesta = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}

if ($accion === 'eliminar') {
    // importar la conexion
    include '../funciones/conexion.php';

    try {
        // Realizar la consulta a la base de datos
        $stmt = $conn->prepare('DELETE FROM tarea WHERE id = ?');
        $stmt->bind_param('i', $id_tarea);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // En caso de error, tomar la exception
        $respuesta = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}
