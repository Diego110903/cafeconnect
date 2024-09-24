import { Ajax, ruta} from "./auxiliares.js";

export function guardarfactura(m) {
    let datos = {
        
        fecha: document.getElementById("fecha").value,
        hora: document.getElementById("hora").value,
        valorfactura: document.getElementById("valorfactura").value,
        mediopago: document.getElementById("mediopago").value,
        id_factura: localStorage.getItem("id_factura"),
    };

    Ajax({
        url: "../control/facturas.php",
        method: m,
        param: datos,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                alert("El registro fue guardado correctamente");
                ruta("listafacturas.html");
            } else {
                // alert("Error en el registro. " + resp.msg);
            }
        }
    });
}

export function listafacturas() {
    localStorage.removeItem("id_factura");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='5' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/facturas.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            // console.log(resp); // Añade este log para ver la respuesta

            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.fecha}</td>
                              <td>${el.hora}</td>
                              <td>${el.valorfactura}</td>
                              <td>${el.mediopago}</td>
                             <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_factura" title='Eliminar' data-id='${el.id}'></button>
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


        


export function buscarfactura(id, send) {
    Ajax({
        url: "../control/facturas.php",
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

export function cargarMediopago() {
    Ajax({
        url: "../control/mediopago.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                let select = document.getElementById("mediopago");
                select.innerHTML = "<option value=''>Seleccione uno</option>";
                resp.data.forEach((mediopago) => {
                    let option = document.createElement("option");
                    option.value = mediopago.id;
                    option.textContent = mediopago.nombre;
                    select.appendChild(option);
                });
            } else {
                alert("Error al cargar : " + resp.msg);
            }
        }
    });
}



export function eliminarfactura(id) {
    let resp = confirm(`¿Desea eliminar el registro de la factura (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/facturas.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code == 200) {
                    listafacturas();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}