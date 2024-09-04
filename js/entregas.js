import { Ajax, ruta } from "./auxiliares.js";

// Función para guardar o actualizar entregas
export function guardarentregas(m) {
    let datos = {
        proveedor: document.getElementById("proveedor").value,
        valorcosto: document.getElementById("valorcosto").value,
        cantidad: document.getElementById("cantidad").value,
        fecha: document.getElementById("fecha").value,
        id_usuario: localStorage.getItem("id_entregas"),
    };

    Ajax({
        url: "../control/entregas.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                alert("El registro fue guardado correctamente");
                ruta("listadeentregas.html");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

// Función para listar entregas
export function listadeentregas() {
    localStorage.removeItem("id_entregas");
    let $tinfo = document.getElementById("tinfo");
    $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only">Cargando...</span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/entregas.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                let data = resp.data;
                let item = "";
                data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.nombre}</td>
                              <td>${el.valorcosto}</td>
                              <td>${el.cantidad}</td>
                              <td>${el.fecha}</td>
                              <td>
                                <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary fa fa-edit u_entregas" title='Editar' data-id='${el.id}'></button>
                                <button type="button" class="btn btn-outline-danger fa fa-trash d_entregas" title='Eliminar' data-id='${el.id}'></button>
                                </div>
                              </td>
                            </tr>`;
                });
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        },
        fError: (err) => {
            $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'>Error al cargar los datos</td></tr>`;
        }
    });
}

// Función para buscar entregas por id
export function buscarentregas(id, send) {
    Ajax({
        url: "../control/entregas.php",
        method: "GET",
        param: { id },
        fSuccess: (resp) => {
            if (resp.code == 200) {
                send(resp.data);
            } else {
                console.error("Error en la petición: " + resp.msg);
            }
        },
        fError: (err) => {
            console.error("Error en la solicitud: " + err);
        }
    });
}


// Función para redirigir a la página de actualización de entregas
export function editarentregas(id) {
    localStorage.setItem("id_entregas", id);
    ruta("actualizarentregas.html?id=" + id);
}

// Función para eliminar una entrega
export function eliminarentregas(id) {
    let resp = confirm(`¿Desea eliminar el registro de la entrega (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/entregas.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code === 200) {
                    listadeentregas();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}

