import { Ajax, ruta } from "./auxiliares.js";

// Función para guardar o actualizar producto
export function guardarproducto(m) {
    let datos = {
        
        nombre: document.getElementById("nombre").value,
        presentacion: document.getElementById("presentacion").value,
        minimostock: document.getElementById("minimostock").value,
        id_producto: localStorage.getItem("id_producto"),
    };

    Ajax({
        url: "../control/productos.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                alert("El registro fue guardado correctamente");
                ruta("listaproducto.html");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

// Función para lista producto
export function listaproducto() {
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='5' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    Ajax({
        url: "../control/productos.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.nombre}</td>
                              <td>${el.presentacion}</td>
                              <td>${el.minimostock}</td>
                              <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-primary fa fa-edit u_producto" title='Editar' data-id='${el.id}'></button>
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_producto" title='Eliminar' data-id='${el.id}'></button>
                                 
                                </div>
                              </td>
                            </tr>`;
                });
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='5' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        }
    });
}

// Función para buscar producto por id
export function buscarproducto(id, send) {
    Ajax({
        url: "../control/productos.php",
        method: "GET",
        param: { id },
        fSuccess: (resp) => {
            if (resp.code == 200) {
                send(resp.data);
            } else {
                alert("Error en la petición\n" + resp.msg);
            }
        }
    });
}


// Función para redirigir a la página de actualización de productos
export function editarproducto(id) {
    localStorage.setItem("id_producto", id);
    ruta("actualizarproducto.html?id=" + id);
}

// Función para eliminar una producto
export function eliminarproducto(id) {
    let resp = confirm(`¿Desea eliminar el registro de la producto (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/productos.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code === 200) {
                    listaproducto();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}