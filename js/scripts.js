eventListeners();

// Lista de proyecto
let listaProyecto = document.querySelector("ul#proyectos");

function eventListeners() {
  // Document Ready
  document.addEventListener("DOMContentLoaded", function () {
    actualizarProgreso();
  });

  // Boton para crear proyecto
  if (document.querySelector(".crear-proyecto a")) {
    document
      .querySelector(".crear-proyecto a")
      .addEventListener("click", nuevoProyecto);
  }

  // Boton para una nueva tarea
  if (document.querySelector(".nueva-tarea")) {
    document
      .querySelector(".nueva-tarea")
      .addEventListener("click", agregarTarea);
  }
  // Botones para las acciones de las tareas
  if (document.querySelector(".listado-pendientes")) {
    document
      .querySelector(".listado-pendientes")
      .addEventListener("click", accionesTareas);
  }
  // Administrador
  if (document.querySelector("a.btn-administrador")) {
    document
      .querySelector("a.btn-administrador")
      .addEventListener("click", mostrarAdministracion);
  }

  if (document.querySelector("#listado-usuarios tbody")) {
    document
      .querySelector("#listado-usuarios tbody")
      .addEventListener("click", seleccionarIdUsuario);
  }

  //Boton agregar usuario al proyecto
  if (document.querySelector(".projecto-usuario")) {
    document
      .querySelector(".projecto-usuario")
      .addEventListener("click", obtenerUsuarioProyecto);
  }

  // Boton eliminar usuario del proyecto
  if (document.querySelector("table tbody")) {
    document
      .querySelector("table tbody")
      .addEventListener("click", seleccionarIdUsuarioHas);
  }
}

function nuevoProyecto(e) {
  e.preventDefault();
  console.log("presionaste en nuevo proyecto");

  // Crea un input para el nombre del nuevo proyecto
  let nuevoProyecto = document.createElement("li");
  nuevoProyecto.innerHTML = '<input type="text" id="nuevo-proyecto">';
  listaProyecto.appendChild(nuevoProyecto);

  // Seleccionar el ID con el nuevo proyecto
  let inputNuevoProyecto = document.querySelector("#nuevo-proyecto");

  // Al presionar enter crear el proyecto

  inputNuevoProyecto.addEventListener("keypress", function (e) {
    let tecla = e.which || e.keyCode;

    if (tecla === 13) {
      guardarProyectoDB(inputNuevoProyecto.value);
      listaProyecto.removeChild(nuevoProyecto);
    }
  });
}

function guardarProyectoDB(nombreProyecto) {
  // Crear el llamado Ajax
  let xhr = new XMLHttpRequest();

  // Enviar datos formdata
  let datos = new FormData();
  datos.append("proyecto", nombreProyecto);
  datos.append("accion", "crear");

  // Abrir la conexion
  xhr.open("POST", "inc/modelos/modelo-proyecto.php", true);

  // En la carga
  xhr.onload = function () {
    if (this.status === 200) {
      // Obtener datos de la respuesta
      let respuesta = JSON.parse(xhr.responseText);
      let proyecto = respuesta.nombre_proyecto,
        id_insertado = respuesta.id_insertado,
        tipo = respuesta.tipo,
        resultado = respuesta.respuesta;

      // Comprobar la insercion
      if (resultado === "correcto") {
        // Fue exitoso
        if (tipo === "crear") {
          // Se creo un nuevo proyecto
          // Inyectar en el HTML
          let nuevoProyecto = document.createElement("li");
          nuevoProyecto.innerHTML = `
            <a href="index.php?id_proyecto=${id_insertado}" id="proyecto:${id_insertado}">
            ${proyecto}
            </a>
          `;
          // Agregar al HTML
          listaProyecto.appendChild(nuevoProyecto);

          // Enviar alerta
          swal({
            title: "Proyecto Creado",
            text: "El proyecto: " + proyecto + " se creo correctamente",
            type: "success",
          }).then((resultado) => {
            if (resultado.value) {
              window.location.href = "index.php?id_proyecto=" + id_insertado;
            }
          });
          // Redireccionar a la nueva URL
        } else {
          // Se actualizo o elimino
        }
      } else {
        // Hubo un error
        swal({
          title: "Error",
          text: "Error!",
          type: "Hubo un error",
        });
      }
    }
  };

  // Enviamos la peticion
  xhr.send(datos);
}

// Agregar una nueva tarea al proyecto actual

function agregarTarea(e) {
  e.preventDefault();

  if (document.querySelector(".nombre-tarea")) {
    var nombreTarea = document.querySelector(".nombre-tarea").value;
  }

  //let indexUsuarioElegido, indexUsuarioElegido;
  if (document.querySelector(".nombre-tarea")) {
    // Obtenemos el index del usuario
    var indexUsuarioElegido = document.querySelector("#usuario-tarea").options
      .selectedIndex;
    // Obtenemos el id del Usuario
    var idUsuarioElegido = document
      .querySelector("#usuario-tarea")
      .item(indexUsuarioElegido).value;

    var nombreUsuario = document
      .querySelector("#usuario-tarea")
      .item(indexUsuarioElegido).innerHTML;
  }

  // Validar que el campo tenga algo escrito
  if (nombreTarea === "" || isNaN(idUsuarioElegido)) {
    swal({
      title: "Error",
      text: "Llene todos los campos de la tarea",
      type: "error",
    });
  } else {
    // La tarea tiene algo, insertar con PHP
    // Crear el llamado a Ajax
    let xhr = new XMLHttpRequest();

    // Crear formdata
    let datos = new FormData();
    datos.append("tarea", nombreTarea);
    datos.append("accion", "crear");
    datos.append("id_proyecto", document.getElementById("id_proyecto").value);
    datos.append("id_usuario", idUsuarioElegido);
    // Abrir la conexion

    xhr.open("POST", "inc/modelos/modelo-tarea.php", true);

    // Ejecutarlo y respuesta

    xhr.onload = function () {
      if (this.status === 200) {
        let respuesta = JSON.parse(xhr.responseText);
        //asignar valores

        let resultado = respuesta.respuesta,
          tarea = respuesta.tarea,
          id_insertado = respuesta.id_insertado,
          tipo = respuesta.tipo;

        if (resultado === "correcto") {
          console.log(respuesta);
          // Se agrego correctamente
          if (tipo === "crear") {
            swal({
              title: "Tarea creada",
              text: "La tarea: " + tarea + " se creo correctamente",
              type: "success",
            });

            // Selecionar el parrafo con la lista vacia
            let parrafoListaVacia = document.querySelectorAll(".lista-vacia");
            if (parrafoListaVacia.length > 0) {
              for (let i = 0; i < parrafoListaVacia.length; i++) {
                parrafoListaVacia[i].remove();
              }
            }
            // Construir el template
            let nuevaTarea = document.createElement("li");

            // Agregamo el ID
            nuevaTarea.id = "tarea:" + id_insertado;

            // Agregar la clase tarea
            nuevaTarea.classList.add("tarea");
            nuevaTarea.setAttribute("id-tarea-usuario", idUsuarioElegido);

            // Construir en el HTML
            nuevaTarea.innerHTML = `
            <p>${tarea}</p>
            <p>${nombreUsuario}</p>
            <div class="acciones">
              <i class="far fa-check-circle"></i>
              <i class="fas fa-trash"></i>
            </div>
            `;

            //Aggregarlo al HTML
            let listado = document.querySelector(".listado-pendientes ul");
            listado.appendChild(nuevaTarea);

            //Limpiar el formulario
            document.querySelector(".agregar-tarea").reset();
            // Actualizar el progreso
            actualizarProgreso();
          }
        } else {
          // HUbo un error
          swal({
            title: "Error",
            text: "Hubo un error",
            type: "error",
          });
        }
      }
    };

    xhr.send(datos);
  }
}

/** Cambia el estado de las tareas o las elimina **/
function accionesTareas(e) {
  e.preventDefault();

  if (e.target.classList.contains("fa-check-circle")) {
    if (e.target.classList.contains("completo")) {
      e.target.classList.remove("completo");
      cambiarEstadoTarea(e.target, 0);
    } else {
      e.target.classList.add("completo");
      cambiarEstadoTarea(e.target, 1);
    }
  }

  if (e.target.classList.contains("fa-trash")) {
    swal({
      title: "Seguro(a) ?",
      text: "Esta accion no de puede deshacer!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "SI, borrar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        let tareaEliminar = e.target.parentElement.parentElement;
        // Borrar de la DB
        eliminarTareaDB(tareaEliminar);
        actualizarProgreso();
        // Borar del HTML
        tareaEliminar.remove();
        swal("Eliminado!", "La tarea fue eliminada.", "success");
      }
    });
  }
}

// Completa o descompleta una tarea

function cambiarEstadoTarea(tarea, estado) {
  let idTarea = tarea.parentElement.parentElement.id.split(":");

  // Crear el llamado a AJAX
  let xhr = new XMLHttpRequest();

  // informacion
  let datos = new FormData();
  datos.append("id", idTarea[1]);
  datos.append("accion", "actualizar");
  datos.append("estado", estado);

  // Abrir la conexion
  xhr.open("POST", "inc/modelos/modelo-tarea.php", true);

  // on load
  xhr.onload = function () {
    if (this.status === 200) {
      // Actualizar el progreso
      actualizarProgreso();
    }
  };

  // Enviamos la peticion
  xhr.send(datos);
}

// Eliminas las tareas de la base de datos

function eliminarTareaDB(tarea) {
  let idTarea = tarea.id.split(":");

  // Crear el llamado a AJAX
  let xhr = new XMLHttpRequest();

  // informacion
  let datos = new FormData();
  datos.append("id", idTarea[1]);
  datos.append("accion", "eliminar");

  // Abrir la conexion
  xhr.open("POST", "inc/modelos/modelo-tarea.php", true);

  // on load
  xhr.onload = function () {
    if (this.status === 200) {
      let respuesta = JSON.parse(xhr.responseText);
      if (respuesta.respuesta === "correcto") {
        //Comprobar que hayan tareas restantes
        let listaTareasRestantes = document.querySelectorAll("li.tarea");
        if (document.querySelectorAll("li.tarea").length === 0) {
          document.querySelectorAll(".listado-pendientes ul").innerHTML =
            "<p class='lista-vacia'>No hay tareas en este proyecto</p>";
        }
        // Actualizar el progreso
        actualizarProgreso();
      }
    }
  };

  // Enviamos la peticion
  xhr.send(datos);
}

// Actualiza el avance del projecto
function actualizarProgreso() {
  // Obtener todas la tareas
  const tareas = document.querySelectorAll("li.tarea");

  // Obtener las tareas completadas
  const tareasCompletadas = document.querySelectorAll("i.completo");

  // Determinar el avance
  const avance = Math.round((tareasCompletadas.length / tareas.length) * 100);

  // Asignar el avance a la barra
  const porcentaje = document.querySelector("#porcentaje");
  let valorPorcentaje = document.querySelector("#numero-porcentaje");

  if (porcentaje && valorPorcentaje) {
    !avance
      ? (valorPorcentaje.innerHTML = "0%")
      : (valorPorcentaje.innerHTML = avance + "%");
    porcentaje.style.width = avance + "%";
  }

  // Mostrar una alerta al completar al 100%
  if (avance === 100) {
    swal({
      title: "Proyecto Terminado",
      text: "Terminaste las tareas del proyecto !!",
      type: "success",
    });
  }
}

/** ADMINISTRACION de Usuarios**/

function mostrarAdministracion() {
  let adminProyectos = (document.querySelector(
    "div.contenido-proyectos"
  ).style.display = "none");
  let administrador = (document.querySelector(
    "div.usuarios-proyecto"
  ).style.display = "block");

  console.log(administrador);
}

function seleccionarIdUsuario(e) {
  //e.preventDefault();

  if (e.target.parentElement.classList.contains("btn-borrar")) {
    let id = e.target.parentElement.getAttribute("data-id");
    console.log("data-id:" + id);
    swal({
      title: "Seguro(a) ?",
      text: "Esta accion no de puede deshacer!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "SI, borrar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        let usuarioEliminar =
          e.target.parentElement.parentElement.parentElement;
        console.log(usuarioEliminar);
        // Borrar de la DB
        eliminarUsuario(id);

        // Borar del HTML
        usuarioEliminar.remove();
        swal("Eliminado!", "La usuario fue eliminada.", "success");
      }
    });
  }
}

function eliminarUsuario(id) {
  // Datos para Ajax
  let datos = new FormData();
  datos.append("idCuenta", id);
  datos.append("accion", "eliminar");

  // AJAX
  // Crear el llamado
  let xhr = new XMLHttpRequest();

  // Abrir la Conexion
  xhr.open("POST", "inc/modelos/modelo-admin.php", true);

  // On load
  xhr.onload = function () {
    if (this.status === 200) {
      let respuesta = JSON.parse(xhr.responseText);
      console.log(respuesta);
    }
  };

  // Enviamos la peticion
  xhr.send(datos);
}

// Obtenemos el id del usuario y el id del proyecto
function obtenerUsuarioProyecto(e) {
  e.preventDefault();

  // Obteneniendo el id del usuario
  let indexUsuario, idUsuario;
  if (document.querySelector("#usuario-proyecto")) {
    indexUsuario = document.querySelector("#usuario-proyecto").options
      .selectedIndex;

    idUsuario = document.querySelector("#usuario-proyecto").item(indexUsuario)
      .value;
  }

  // Obtenemos el id del proyecto
  let idProyecto = document.querySelector("#id_usuario_proyecto").value;

  if (idUsuario && !isNaN(idUsuario)) {
    agregarUsuarioProyecto(idUsuario, idProyecto);
  } else {
    swal({
      title: "Advertencia",
      text: "Debe elegir un usuario",
      type: "warning",
    });
  }
}

// Agregarmos el usuario al entidad has
function agregarUsuarioProyecto(idUsuario, idProyecto) {
  // Preparemo los datos
  let datos = new FormData();
  datos.append("idUsuario", idUsuario);
  datos.append("idProyecto", idProyecto);
  datos.append("accion", "crear");

  // LLamamos a AJAX
  let xhr = new XMLHttpRequest();

  // Abrimos la conexion
  xhr.open("POST", "inc/modelos/modelo-proyecto_has_usuario.php", true);

  // On load
  xhr.onload = function () {
    if (this.status === 200) {
      let respuesta = JSON.parse(xhr.responseText);
      if (respuesta.resultado === "correcto") {
        swal({
          title: "Exitoso",
          text: "Usuario agregado Correctamente",
          type: "success",
        }).then(() => {
          let tableUsuarioHas = document.querySelector("table tbody");
          // Creamos los elementos para el template
          let nuevoUsuarioHas = document.createElement("tr"),
            tdNombre = document.createElement("td"),
            tdRol = document.createElement("td"),
            tdAcciones = document.createElement("td");

          nuevoUsuarioHas.setAttribute("phu-id", respuesta.id_insertado);
          nuevoUsuarioHas.setAttribute("id-usuario", respuesta.id_usuario);
          tdNombre.innerHTML = respuesta.usuario;
          tdRol.innerHTML = respuesta.tipoUsuario;
          tdAcciones.innerHTML = `
          <i data-id="${respuesta.id_insertado}" class="fas fa-trash btn-borrar btn"></i>
          `;

          nuevoUsuarioHas.appendChild(tdNombre);
          nuevoUsuarioHas.appendChild(tdRol);
          nuevoUsuarioHas.appendChild(tdAcciones);

          // Insertadmos el nuevo registro en la tabla
          tableUsuarioHas.appendChild(nuevoUsuarioHas);

          let selectUsuario = document.getElementById("usuario-proyecto");
          selectUsuario.selectedIndex = 0;
          let optionSeleccionado = document.querySelector(
            "#option-u-" + idUsuario
          );

          // Creando nuevo option en el select usuario-tarea
          let selectUsuarioTarea = document.querySelector("#usuario-tarea");

          let optionUsuarioTarea = document.createElement("option");
          optionUsuarioTarea.innerHTML = respuesta.usuario;
          optionUsuarioTarea.value = respuesta.id_usuario;
          optionUsuarioTarea.id = "option-ut-" + respuesta.id_usuario;

          selectUsuarioTarea.appendChild(optionUsuarioTarea);

          optionSeleccionado.remove();
          console.log(respuesta);
        });
      }
    }
  };

  // Enviamos los datos
  xhr.send(datos);
}

function seleccionarIdUsuarioHas(e) {
  e.preventDefault();

  if (e.target.classList.contains("btn-borrar")) {
    let id = e.target.parentElement.parentElement.getAttribute("phu-id");
    let idUsuario = e.target.parentElement.parentElement.getAttribute(
      "id-usuario"
    );
    swal({
      title: "Seguro(a) ?",
      text: "Esta accion no de puede deshacer!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "SI, borrar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        let usuarioEliminar = e.target.parentElement.parentElement;

        // Agregar Usuario al Select
        let selectUsuario = document.querySelector("#usuario-proyecto");
        let nuevoOption = document.createElement("option");
        nuevoOption.value = idUsuario;
        nuevoOption.id = "option-u-" + idUsuario;
        nuevoOption.innerText =
          e.target.parentElement.parentElement.firstElementChild.innerHTML;

        selectUsuario.appendChild(nuevoOption);

        // Eliminar del Select usuario tarea
        let idUsuarioTarea = e.target.getAttribute("data-id");

        let option = document.querySelector(
          "#usuario-tarea #option-ut-" + idUsuarioTarea
        );
        option.remove();

        // Borrar del HTML
        usuarioEliminar.remove();

        // Eliminar tareas usuario
        eliminarUsuarioTarea(idUsuario);

        // Borrar de la DB
        eliminarUsuarioHas(id);
        actualizarProgreso();
      }
    });
  }
}

function eliminarUsuarioHas(id) {
  // Preparamos los datos
  let datos = new FormData();
  datos.append("idPhu", id);
  datos.append("accion", "eliminar");

  // Llamamos a AJAX
  let xhr = new XMLHttpRequest();

  // Abrimos la conexion
  xhr.open("POST", "inc/modelos/modelo-proyecto_has_usuario.php", true);

  // On load
  xhr.onload = function () {
    if (this.status === 200) {
      let respuesta = JSON.parse(xhr.responseText);
      if (respuesta.respuesta === "correcto") {
        console.log(respuesta);
        swal("Eliminado!", "La usuario fue eliminada.", "success");
      }
    }
  };

  // Enviamos los datos
  xhr.send(datos);
}

function eliminarUsuarioTarea(id) {
  let tarea = document.querySelectorAll(".listado-pendientes ul li");

  for (let index = 0; index < tarea.length; index++) {
    if (tarea[index].getAttribute("id-tarea-usuario") === id) {
      tarea[index].remove();
    }
    console.log("parametro id: " + id);
  }
}
