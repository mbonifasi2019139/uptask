<div class="contenido-proyectos">

    <?php
    $proyecto = obtenerNombreProyecto($id_proyecto);
    if ($proyecto) : ?>

        <h1>Proyecto Actual:
            <?php foreach ($proyecto as $nombre) : ?>
                <span><?php echo $nombre['nombre']; ?></span>
            <?php endforeach; ?>

        </h1>

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



    <h2>Listado de tareas:</h2>

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
                <?php endforeach; ?>
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
            <div id="porcentaje" class="porcentaje">

            </div>
        </div>
    </div>
</div>