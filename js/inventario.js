import { Ajax, ruta } from "./auxiliares.js";

// Función para guardar o actualizar inventario
export function guardarinventario(m) {
    let datos = {
        valorventa: document.getElementById("valorventa").value,
        stock: document.getElementById("stock").value,
        producto: document.getElementById("producto").value,
        entregas: document.getElementById("entregas").value, // Obtener el valor de entregas correctamente
        idProductoFK: document.getElementById("producto").dataset.id || "", // Asume que el ID está en un data attribute
        idEntregasFK: document.getElementById("entregas").dataset.id || "" // Asume que el ID está en un data attribute
    };  

    Ajax({
        url: "../control/inventario.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                alert("El registro fue guardado correctamente");
                ruta("listainventario.html");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

// Función para listar inventario
export function listainventario() {
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='5' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/inventario.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code === 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <td>${el.producto}</td>
                              <td>${el.entregas}</td>
                              <td>${el.valorventa}</td>
                              <td>${el.stock}</td>
                              <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-primary fa fa-edit u_inventario" title='Editar' data-idproducto='${el.idProductoFK}' data-identregas='${el.idEntregasFK}'></button>
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_inventario" title='Eliminar' data-idproducto='${el.idProductoFK}' data-identregas='${el.idEntregasFK}'></button>
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

// Función para cargar proveedores
export function cargarProveedores() {
    Ajax({
        url: "../control/proveedores.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                let select = document.getElementById("proveedor");
                select.innerHTML = "<option value=''>Seleccione uno</option>";
                resp.data.forEach((proveedor) => {
                    let option = document.createElement("option");
                    option.value = proveedor.id;
                    option.textContent = proveedor.nombre;
                    select.appendChild(option);
                });
            } else {
                alert("Error al cargar los proveedores: " + resp.msg);
            }
        },
        fError: (err) => {
            alert("Error al cargar los proveedores");
        }
    });
}

// Función para cargar entregas
export function cargarEntregas() {
    Ajax({
        url: "../control/entregas.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                let select = document.getElementById("entregas");
                select.innerHTML = "<option value=''>Seleccione uno</option>";
                resp.data.forEach((entrega) => {
                    let option = document.createElement("option");
                    option.value = entrega.id;
                    option.textContent = entrega.nombre;
                    select.appendChild(option);
                });
            } else {
                alert("Error al cargar las entregas: " + resp.msg);
            }
        },
        fError: (err) => {
            alert("Error al cargar las entregas");
        }
    });
}

// Función para buscar inventario por id
export function buscarinventario(idProductoFK, idEntregasFK, send) {
    Ajax({
        url: "../control/inventario.php",
        method: "GET",
        param: { idProductoFK, idEntregasFK },
        fSuccess: (resp) => {
            if (resp.code == 200) {
                send(resp.data); 
            } else {
                alert("Error en la petición\n" + resp.msg);
            }
        }
    });
}

// Función para redirigir a la página de actualización de inventario
export function editarinventario(idProductoFK, idEntregasFK) {
    localStorage.setItem("idProductoFK", idProductoFK);
    localStorage.setItem("idEntregasFK", idEntregasFK);
    ruta("actualizarinventario.html");
}

// Función para eliminar un inventario
export function eliminarinventario(idProductoFK, idEntregasFK) {
    let resp = confirm(`¿Desea eliminar el registro del inventario con Producto (#${idProductoFK}) y Entregas (#${idEntregasFK})?`);
    if (resp) {
        Ajax({
            url: "../control/inventario.php",
            method: "DELETE",
            param: { 
                idProductoFK: idProductoFK,
                idEntregasFK: idEntregasFK
            },
            fSuccess: (resp) => {
                if (resp.code === 200) {
                    // Llamar a la función que lista el inventario nuevamente
                    listainventario();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}

