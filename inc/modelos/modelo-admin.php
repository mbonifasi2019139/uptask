<?php

$accion = filter_var($_POST['accion'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
$tipoUsuario = (int) filter_var($_POST['tipoUsuario'], FILTER_SANITIZE_NUMBER_INT);
$idCuenta = (int) filter_var($_POST['idCuenta'], FILTER_SANITIZE_NUMBER_INT);

if ($accion === 'crear') {
    //Codigo para crear los administradores


    // hashear passwords
    $opciones = array(
        'cost' => 12
    );

    $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

    // importar la conexion
    include '../funciones/conexion.php';

    try {
        // Realizar la consulta a la base de datos
        $stmt = $conn->prepare('INSERT INTO usuario (usuario, password, idtipousuario) values (?, ?, ?)');
        $stmt->bind_param('ssi', $usuario, $hash_password, $tipoUsuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'id_insertado' => $stmt->insert_id,
                'tipo' => $accion
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

if ($accion === 'login') {
    // escribir codigo para loguear los administradores
    include '../funciones/conexion.php';

    try {
        // Seleccionar el administrador de la base de datos

        $stmt = $conn->prepare('SELECT usuario, id, password, idtipousuario FROM usuario WHERE usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();

        // Loguear el usuario
        $stmt->bind_result($nombre_usuario, $id_usuario, $password_usuario, $idTipoUsuario);
        $stmt->fetch();

        if ($nombre_usuario) {
            // EL usuario existe, verificar el password
            if (password_verify($password, $password_usuario)) {
                // Inicar la session
                session_start();
                $_SESSION['nombre'] = $usuario;
                $_SESSION['id'] = $id_usuario;
                $_SESSION['password'] = $password_usuario;
                $_SESSION['tipo'] = $idTipoUsuario;
                $_SESSION['login'] = true;
                // Login correcto
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'nombre' => $nombre_usuario,
                    'tipo' => $accion
                );
            } else {
                // Login incorrecto
                $respuesta = array(
                    'error' => 'Password Incorrecta'
                );
            }
        } else {
            $respuesta = array(
                'error' => 'El usuario no existe'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // En caso de error, tomar la exception
        $respuesta = array(
            'respuesta' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}

if ($accion === 'editar') {

    // hashear passwords
    $opciones = array(
        'cost' => 12
    );

    $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

    include '../funciones/conexion.php';

    try {
        // Seleccionar el administrador de la base de datos

        $stmt = $conn->prepare('UPDATE usuario SET usuario = ?, password = ?, idtipousuario = ? WHERE id = ?');
        $stmt->bind_param('ssii', $usuario, $hash_password, $tipoUsuario, $idCuenta);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'tipo' => $accion
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
            'respuesta' => $e->getMessage()
        );
    }

    echo json_encode($respuesta);
}

if ($accion === "eliminar") {
    include '../funciones/conexion.php';

    try {
        //code...
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->bind_param('i', $idCuenta);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'tipo' => $accion
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " + $e->getMessage();
    }

    echo json_encode($respuesta);
}
