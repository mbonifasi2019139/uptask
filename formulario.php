<?php
include 'inc/funciones/sesiones.php';
include 'inc/funciones/funciones.php';
include 'inc/templates/header.php';

if (isset($_GET['cerrar_sesion'])) {
    $_SESSION = array();
}

if (isset($_GET['id'])) {
    $accion = "editar";
    $idEditar = (int)$_GET['id'];
    $resultado = obtenerUsuario((int)$idEditar);
    $usuario = $resultado->fetch_assoc();
} else {
    $accion = "crear";
}
?>
<pre>
    <?php
    echo var_dump($usuario);
    ?>
</pre>
<div class="contenedor-formulario">
    <h1>UpTask <span><?php echo (isset($idEditar) ? "Editar Cuenta" : "Crear Cuenta") ?></span></h1>
    <form id="formulario" class=" caja-login" method="post">
        <div class="campo">
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario" placeholder="User" value="<?php echo ($usuario['usuario']) ? $usuario['usuario'] : ""; ?>">
        </div>
        <div class="campo">
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" placeholder="<?php echo ($accion == "crear") ? "Password" : "New Password"; ?>">
        </div>
        <div class="campo">
            <select name="tipoUsuario" id="tipo-usuario" required>
                <option disabled <?php echo ($accion == "crear") ? "selected" : ""; ?>>-- Seleccione un Tipo --</option>
                <?php
                $tipoUsuario = obtenerTipoUsuario();

                foreach ($tipoUsuario as $rol) : ?>
                    <option value="<?php echo $rol['id']; ?>" <?php echo ($usuario['nombre'] == $rol['nombre']) ? "selected" : ""; ?>>
                        <?php echo $rol['nombre']; ?>
                    </option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="campo enviar">
            <input type="hidden" id="tipo" value="<?php echo $accion ?>">
            <?php
            if ($accion === "editar") : ?>
                <input type="hidden" id="idCuenta" value="<?php echo $idEditar; ?>">
            <?php
            endif; ?>
            <input type="submit" class="boton" value="<?php echo $accion ?> Cuenta">
        </div>
    </form>
</div>
<?php include 'inc/templates/footer.php' ?>