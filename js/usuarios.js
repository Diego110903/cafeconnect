import { Ajax, ruta } from "./auxiliares.js";

export function guardarUsuario(m) {
    let datos = {
        nombre: document.getElementById("nombre").value,
        apellidos: document.getElementById("apellidos").value,
        email: document.getElementById("email").value,
        rol: document.getElementById("rol").value,
        password: document.getElementById("password") ? document.getElementById("password").value : "",
        confirmar: document.getElementById("confirmar") ? document.getElementById("confirmar").value : "",
        id_usuario: localStorage.getItem("id_usuario"),
    };

    Ajax({
        url: "../control/usuario.php", 
        method: m, 
        param: datos, 
        fSuccess: (resp) => {
            if (resp.code == 200) {
                alert("El registro fue guardado correctamente");
                ruta("listausuario.html");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

export function listaUsuario() {
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    Ajax({
        url: "../control/usuario.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.rol}</td>
                              <td>${el.nombre}</td>
                              <td>${el.apellidos}</td>
                              <td>${el.email}</td>
                              <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-primary fa fa-edit u_usuario" title='Editar' data-id='${el.id}'></button>
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_usuario" title='Eliminar' data-id='${el.id}'></button>
                                  <button type="button" class="btn btn-outline-success fa fa-code-branch p_usuario" title='Permisos' data-id='${el.id}'></button>
                                </div>
                              </td>
                            </tr>`;
                });
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        }
    });
}

export function buscarusuario(id, send) {
    Ajax({
        url: "../control/usuario.php",
        method: "GET",
        param: { id },
        fSuccess: (resp) => {
            if (resp.code == 200) {
                send(resp.data);
            } else {
                alert("Error en la petición\n" + resp.msg);
            }
        },
        fError: (err) => {
            alert("Error al buscar el usuario");
        }
    });
}

export function editarusuario(id) {
    localStorage.setItem("id_usuario", id);
    ruta("actualizarusuario.html?id=" + id);
}

export function eliminarusuario(id) {
    let resp = confirm(`¿Desea eliminar el registro del usuario (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/usuario.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code == 200) {
                    listaUsuario();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            },
            fError: (err) => {
                alert("Error al eliminar el usuario");
            }
        });
    }
}
