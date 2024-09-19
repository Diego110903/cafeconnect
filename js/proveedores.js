import { Ajax, ruta} from "./auxiliares.js";

export function guardarproveedor(m) {
    let datos = {
        
        nit: document.getElementById("nit").value,
        nombre: document.getElementById("nombre").value,
        apellidos: document.getElementById("apellidos").value,
        email: document.getElementById("email").value,
        celular: document.getElementById("celular").value,
        ncuenta: document.getElementById("ncuenta").value, // Ajusta el nombre del campo si es necesario
        tipocuenta: document.getElementById("tipocuenta").value, 
        banco: document.getElementById("banco").value, 
        id_proveedor: localStorage.getItem("id_proveedor"),
    };

    Ajax({
        url: "../control/proveedores.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                alert("El registro fue guardado correctamente");
                ruta("listaproveedores.html");
            } else {
                // alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

export function listaProveedores() {
    localStorage.removeItem("id_proveedor");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='8' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/proveedores.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            // console.log(resp); // Añade este log para ver la respuesta

            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.nit}</td>
                              <td>${el.nombre}</td>
                              <td>${el.apellidos}</td>
                              <td>${el.email}</td>
                              <td>${el.celular}</td>
                              <td>
                                ${el.ncuenta}
                                <br><small class='text-primary'>${el.tipocuenta}</small> / <small class='text-success'>${el.banco}</small>
                              </td>
                              <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-primary fa fa-edit u_proveedor" title='Editar' data-id='${el.id}'></button>
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_proveedor" title='Eliminar' data-id='${el.id}'></button>
                                </div>
                              </td>
                            </tr>`;
                });
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='8' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        }
    });
}

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
        }
    });
}
        


export function buscarproveedor(id, send) {
    Ajax({
        url: "../control/proveedores.php",
        method: "GET",
        param: { id },
        fSuccess: (resp) => {
            if (resp.code == 200) {
                send(resp.data);
            } else {
                // alert("Error en la petición\n" + resp.msg);
            }
        }
    });
}

export function editarproveedor(id) {
    
    localStorage.setItem("id_proveedor", id);
    ruta("actualizarproveedor.html?id=" + id);
}

export function eliminarproveedor(id) {
    let resp = confirm(`¿Desea eliminar el registro del proveedor (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/proveedores.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code == 200) {
                    listaProveedores();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}


