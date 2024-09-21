import { Ajax, ruta } from "./auxiliares.js";

// Función para guardar o actualizar ítems
export function guardaritems(m) {
    let datos = {
        IdproductoFK: document.getElementById("id_producto").value,
        IdFacturaFK: document.getElementById("id_factura").value,
        ItemCantidad: document.getElementById("cantidad").value,
        ItemValorTotal: document.getElementById("valor_total").value,
        id_item: localStorage.getItem("id_item"),
    };

    Ajax({
        url: "../control/items.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                alert("El registro fue guardado correctamente");
                ruta("listaitems.html");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        },
        fError: (err) => {
            alert("Error en la conexión: " + err);
        }
    });
}

// Función para listar ítems
export function listaitems() {
    localStorage.removeItem("id_item");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='7' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only">Cargando...</span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/items.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.idproducto}</th>
                              <td>${el.idfactura}</td>
                              <td>${el.cantidad}</td>
                              <td>${el.valorunitario}</td> <!-- Aquí se muestra el valor unitario -->
                              <td>${el.valortotal}</td>
                              <td>
                                <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary fa fa-edit u_items" title='Editar' data-id='${el.id_item}'></button>
                                <button type="button" class="btn btn-outline-danger fa fa-trash d_items" title='Eliminar' data-id='${el.id_item}'></button>
                                </div>
                              </td>
                            </tr>`;
                });
                
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='7' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        },
      
    });
}

// Función para buscar ítems por id
export function buscaritems(id, send) {
    Ajax({
        url: "../control/items.php",
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
            alert("Error en la conexión: " + err);
        }
    });
}

// Función para redirigir a la página de actualización de ítems
export function editaritems(id) {
    localStorage.setItem("id_item", id);
    ruta("actualizaritems.html?id=" + id);
}

// Función para eliminar un ítem
export function eliminaritems(id) {
    let resp = confirm(`¿Desea eliminar el registro del ítem (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/items.php",
            method: "DELETE",
            param: { id_item: id },
            fSuccess: (resp) => {
                if (resp.code === 200) {
                    listaitems();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            },
            fError: (err) => {
                alert("Error en la conexión: " + err);
            }
        });
    }
}






