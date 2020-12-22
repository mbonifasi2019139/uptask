<aside class="contenedor-proyectos">
    <?php
    if ($_SESSION['tipo'] === 1) { ?>
        <div class="panel administrador">
            <a href="#" class="boton btn-administrador <?php echo $_SESSION['nombre']; ?>">Administrar <i class="fas fa-users-cog"></i></a>
        </div>
    <?php
    } ?>

    <div class="panel crear-proyecto">
        <a href="#" class="boton">Nuevo Proyecto <i class="fas fa-plus"></i> </a>
    </div>

    <div class="panel lista-proyectos">
        <h2>Proyectos</h2>
        <ul id="proyectos">
            <?php
            $proyectos = obtenerProyectos();
            if ($proyectos) {
                foreach ($proyectos as $proyecto) { ?>
                    <li>
                        <a href="index.php?id_proyecto=<?php echo $proyecto['id']; ?>" id="proyecto:<?php echo $proyecto['id']; ?>">
                            <?php echo $proyecto['nombre']; ?>
                        </a>
                    </li>
            <?php }
            } ?>
        </ul>
    </div>
</aside>