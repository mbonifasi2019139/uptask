<?php

$accion = filter_var($_POST['accion'], FILTER_SANITIZE_STRING);
$idUsuario = filter_var($_POST['idUsuario'], FILTER_SANITIZE_NUMBER_INT);
$idProyecto = filter_var($_POST['idProyecto'], FILTER_SANITIZE_NUMBER_INT);
$idPhu = filter_var($_POST['idPhu'], FILTER_SANITIZE_NUMBER_INT);

if ($accion === "crear") {
    include '../funciones/conexion.php';
    include '../funciones/funciones.php';
    try {

        $stmt = $conn->prepare("INSERT INTO proyecto_has_usuario (id_proyecto, id_usuario) VALUES(?,?)");
        $stmt->bind_param("ii", $idProyecto, $idUsuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {

            $usuarioIsertado = obtenerUsuarioProyectoHas($idUsuario, $idProyecto);
            $usuario = mysqli_fetch_assoc($usuarioIsertado);

            // Datos para el template
            $nombreUsuario = $usuario['usuario'];
            $tipoUsuario = $usuario['nombre'];

            $respuesta = array(
                'resultado' => 'correcto',
                'id_insertado' => $stmt->insert_id,
                'usuario' => $nombreUsuario,
                'tipoUsuario' => $tipoUsuario
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

if ($accion === "eliminar") {
    include '../funciones/conexion.php';



    try {

        $stmt = $conn->prepare("DELETE FROM proyecto_has_usuario WHERE id = ?");
        $stmt->bind_param("i", $idPhu);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'accion' => $accion
            );
        } else {
            $respuesta = array(
                'respuesta' => "error",
                'accion' => $accion
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
