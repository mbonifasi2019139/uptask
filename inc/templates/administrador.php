<div class="usuarios-proyecto" hidden>
    <h1><strong>Scrum Master - Administrador</strong></h1>
    <hr>
    <div class="usuarios">
        <h2>Usuarios</h2>
        <table id="listado-usuarios" style="width: 100%;">
            <thead>
                <th>Usuario</th>
                <th>Tipo Usuarios</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php
                $usuarios = obtenerUsuarios();
                if ($usuarios->num_rows > 0) :
                    foreach ($usuarios as $usuario) :
                ?>
                        <tr>
                            <td><?php echo $usuario["usuario"]; ?></td>
                            <td><?php echo $usuario["nombre"]; ?></td>
                            <td>
                                <a class="btn-editar btn" href="formulario.php?id=<?php echo $usuario['id'] ?>">
                                    <i class=" fas fa-pen-square"></i>
                                </a>
                                <button data-id="<?php echo $usuario['id']; ?>" type="button" class="btn-borrar btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
    <a href="formulario.php" class="boton">Crear Nuevo Usuario</a>
</div>