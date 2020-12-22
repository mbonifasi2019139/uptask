eventListeners();

function eventListeners() {
  document
    .querySelector("#formulario")
    .addEventListener("submit", validarRegistro);
}

function validarRegistro(e) {
  e.preventDefault();

  const usuario = document.getElementById("usuario").value,
    password = document.getElementById("password").value,
    tipo = document.querySelector("#tipo").value;

  let tipoUsuario, indexTipoUsuario;
  let idCuenta;

  if (document.getElementById("tipo-usuario")) {
    indexTipoUsuario = document.getElementById("tipo-usuario").options
      .selectedIndex;

    tipoUsuario = document.getElementById("tipo-usuario").item(indexTipoUsuario)
      .value;
  }

  if (document.querySelector("#idCuenta")) {
    idCuenta = document.querySelector("#idCuenta").value;
  }

  if (usuario === "" || password === "") {
    // La validacion fallo
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ambos campos son obligatorios!",
    });
  } else {
    // Ambos campos son correctos, madar ejecutar Ajax
    // Datos que se enviar al servidor
    let datos = new FormData();
    datos.append("usuario", usuario);
    datos.append("password", password);
    datos.append("accion", tipo);

    if (indexTipoUsuario) {
      datos.append("tipoUsuario", tipoUsuario);
    }

    if (idCuenta) {
      datos.append("idCuenta", idCuenta);
    }

    // Crear el llamado a Ajax
    let xhr = new XMLHttpRequest();

    // Abrir la conexion
    xhr.open("POST", "inc/modelos/modelo-admin.php", true);

    // retorno de datos
    xhr.onload = function () {
      if (this.status === 200) {
        let respuesta = JSON.parse(xhr.responseText);

        console.log(respuesta);
        // Si la respuesta es correcta
        if (respuesta.respuesta === "correcto") {
          // SI es un nuevo usuario
          if (respuesta.tipo === "crear") {
            swal({
              title: "Usuario Creado",
              text: "El usuario se creo correctamente",
              type: "success",
            }).then((resultado) => {
              if (resultado.value) {
                window.location.href = "index.php";
              }
            });
          } else if (respuesta.tipo === "login") {
            swal({
              title: "Login Correcto",
              text: "Presiona OK para abrir el dashboard",
              type: "success",
            }).then((resultado) => {
              if (resultado.value) {
                window.location.href = "index.php";
              }
            });
          } else if (respuesta.tipo === "editar") {
            swal({
              title: "Editado Correctamente",
              text: "Presiona OK para regresar al dashboard",
              type: "success",
            }).then((resultado) => {
              if (resultado.value) {
                window.location.href = "index.php";
              }
            });
          }
        } else {
          // Hubo un error
          swal({
            title: "Error",
            text: "Hubo un error",
            type: "error",
          });
        }
      }
    };

    // Enviamos los datos
    xhr.send(datos);
  }
}
