<?php

$accion = filter_var($_POST['accion'], FILTER_SANITIZE_STRING);
$idUsuario = filter_var($_POST['idUsuario'], FILTER_SANITIZE_NUMBER_INT);
$idProyecto = filter_var($_POST['idProyecto'], FILTER_SANITIZE_NUMBER_INT);

if ($accion === "crear") {
    include '../funciones/conexion.php';
    try {

        $stmt = $conn->prepare("INSERT INTO proyecto_has_usuario (id_proyecto, id_usuario) VALUES(?,?)");
        $stmt->bind_param("ii", $idProyecto, $idUsuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'resultado' => 'correcto',
                'id_insertado' => $stmt->insert_id,
                'id_proyecto' => $idProyecto,
                'id_usuario' => $idUsuario
            );
        } else {
            $respuesta = array(
                'resultado' => 'error'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $respuesta = array(
            'respuesta' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}
