<?php
include 'inc/funciones/sesiones.php';
include 'inc/funciones/funciones.php';
include 'inc/templates/header.php';
include 'inc/templates/barra.php';

echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
// Obtener el ID de la URL
if (isset($_GET['id_proyecto'])) {
    $id_proyecto = $_GET['id_proyecto'];
}

?>
<div class="contenedor">
    <?php include 'inc/templates/sidebar.php'; ?>
    <main class="contenido-principal">
        <?php include 'inc/templates/admin-proyecto.php'; ?>
        <?php
        if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1) {
            include 'inc/templates/administrador.php';
        }
        ?>
    </main>

</div>
<!--.contenedor-->

<?php include 'inc/templates/footer.php' ?>