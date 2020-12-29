<div class="contenido-proyectos">

    <?php
    $proyecto = obtenerNombreProyecto($id_proyecto);
    if ($proyecto) : ?>

        <h1>Proyecto Actual:
            <?php foreach ($proyecto as $nombre) : ?>
                <span><?php echo $nombre['nombre']; ?></span>
            <?php endforeach; ?>

        </h1>
        <hr>
        <div class="usuarios">
            <h2>Participantes del Proyecto:</h2>
            <?php
            if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1) {
            ?>
                <div class="agregar-usuario">
                    <form action="#" class="agregar-usuario">
                        <div class="campo">
                            <label for="tarea">Usuario:</label>
                            <select name="usuario" id="usuario-proyecto" required>
                                <option disabled selected>-- Seleccione un Usuario --</option>
                                <?php
                                $usuarios = obtenerUsuarioNoRegistrados($id_proyecto);
                                if ($usuarios->num_rows > 0) :
                                    foreach ($usuarios as $usuario) :
                                ?>
                                        <option value="<?php echo $usuario['id']; ?>" id="option<?php echo $usuario['id'] ?>">
                                            <?php echo $usuario['usuario']; ?>
                                        </option>
                                <?php
                                    endforeach;
                                endif; ?>
                            </select>
                        </div>
                        <div class="campo enviar">
                            <input type="hidden" value="<?php echo $id_proyecto ?>" id="id_usuario_proyecto">
                            <input type="submit" class="boton projecto-usuario" value="Agregar">
                        </div>
                    </form>
                </div>
            <?php
            } ?>
            <table class="lista-usuarios" style="width: 100%;">
                <thead>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <?php
                    if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1) : ?>
                        <th>Acciones</th>
                    <?php
                    endif;
                    ?>
                </thead>
                <tbody>
                    <?php

                    $usuario_has_proyecto = obtenerUsuarioDelHas($id_proyecto);
                    if ($usuario_has_proyecto->num_rows > 0) {
                        foreach ($usuario_has_proyecto as $usuarioHas) : ?>
                            <tr style="border: 1px solid #e1e1e1;" phu-id="<?php echo $usuarioHas['idPhu']; ?>">
                                <td><?php echo $usuarioHas['usuario']; ?></td>
                                <td><?php echo $usuarioHas['nombre']; ?></td>
                                <?php
                                if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1) : ?>
                                    <td>

                                        <i data-id="<?php echo $usuarioHas['id']; ?>" class="fas fa-trash btn-borrar btn"></i>

                                    </td>
                                <?php
                                endif;
                                ?>
                            </tr>
                    <?php
                        endforeach;
                    } ?>
                </tbody>
            </table>
        </div>

        <!-- -- Tareas del Proyecto -- -->
        <h2>Listado de tareas:</h2>
        <form action="#" class="agregar-tarea">
            <div class="campo">
                <label for="tarea">Tarea:</label>
                <input type="text" placeholder="Nombre Tarea" class="nombre-tarea">
            </div>
            <div class="campo enviar">
                <input type="hidden" value="<?php echo $id_proyecto; ?>" id="id_proyecto">
                <input type="submit" class="boton nueva-tarea" value="Agregar">
            </div>
        </form>
    <?php
    else :
        // Si no hay proyecto seleccionados
        echo "<p>Selecciona un Proyecto a la izquierda</p>";
    endif; ?>

    <div class="listado-pendientes">
        <ul>
            <?php
            // Obtiene las tareas del proyecto actual
            $tareas = obtenerTareasProyecto((int)$id_proyecto);
            if ($tareas->num_rows > 0) {
                // Si hay tareas
                foreach ($tareas as $tarea) : ?>
                    <li id="tarea:<?php echo $tarea['id'] ?>" class="tarea">
                        <p><?php echo $tarea['nombre'] ?></p>
                        <div class="acciones">
                            <i class="far fa-check-circle <?php echo ($tarea['estado'] === '1' ? 'completo' : ''); ?>"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    </li>
                <?php
                endforeach; ?>
            <?php
            } else {
                // No hay tareas
                echo "<p class='lista-vacia'>No hay tareas en este proyecto</p>";
            }
            ?>
        </ul>
    </div>
    <div class="avance">
        <h2>Avance del Proyecto: </h2>
        <div id="barra-avance" class="barra-avance">
            <p id="numero-porcentaje"></p>
            <div id="porcentaje" class="porcentaje">

            </div>
        </div>
    </div>
</div>