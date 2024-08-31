function ruta(url="",blank = undefined){
    //if(blank===undefined) {window.location.href = url} else {window.open(url)}
    (blank===undefined) ? window.location.href = url : window.open(url)
 }
 
 const Ajax = async (info)=>{
    let {url, method, param, fSuccess} = info
    if(param !== undefined && method==="GET") url += "?"+ new URLSearchParams(param)
    if (method === "GET") method={method,headers: {'Content-Type':'application/json'}}    
    if (method === "POST" || method === "PUT" || method === "DELETE") method={method,headers: {'Content-Type':'application/json'},body: JSON.stringify(param)}
    
    try{
        //console.log(url,method)
        let resp = await fetch(url,method);
        if(!resp.ok) throw {status: resp.status, msg: resp.statusText};
        let respJson =  await resp.json();  
        fSuccess(respJson)
    }catch(e){
       fSuccess({code: e.status, msg: e.msg})
    }
 }

function validarToken() {
    if (localStorage.getItem("token")) {
        const div_info_user = document.getElementById("info_user");
        if (div_info_user) {
            div_info_user.innerHTML = `${localStorage.getItem("user")}`;
        }

        if (location.pathname.includes("registrarusuario") | location.pathname.includes("actualizarusuario")) {
            Rol();
            if (location.pathname.includes("actualizarusuario")) {
                setTimeout(() => {
                    let $form = document.getElementById("form-act_usuario");
                    buscarusuario(localStorage.getItem("id_usuario"), (resp) => {
                        resp.forEach((el) => {
                            $form.id_usuario.value = el.id;
                            $form.nombre.value = el.nombre;
                            $form.apellidos.value = el.apellidos;
                            $form.email.value = el.email;
                            $form.rol.value = el.rol;
                       })
                    })
                 },100)
              }
           }

           if (location.pathname.includes("registrarentregas") | location.pathname.includes("actualizarentregas")) {
            Rol();
            if (location.pathname.includes("actualizarentregas")) {
                setTimeout(() => {
                    let $form = document.getElementById("form-act_entregas");
                    buscarusuario(localStorage.getItem("id_entregas"), (resp) => {
                        resp.forEach((el) => {
                            $form.entregas.value = el.identregas;
                            $form.proveedor.value = el.idproveedor
                            $form.valorcosto.value = el.valorcosto;
                            $form.cantidad.value = el.cantidad;
                            $form.fn.value = el.fn;
                       })
                    })
                 },100)
              }
           }

           if (location.pathname.includes("registrarproveedor") | location.pathname.includes("actualizarproveedor")) {
            banco();
            if (location.pathname.includes("actualizarproveedor")) {
                setTimeout(() => {
                    let $form = document.getElementById("form-act_proveedor");
                    buscarproveedor(localStorage.getItem("id_proveedor"), (resp) => {
                        resp.forEach((el) => {
                            $form.id_proveedor.value = el.proveedor;
                            $form.nit.value = el.nit;
                            $form.nombre.value = el.nombre;
                            $form.apellidos.value = el.apellidos;
                            $form.email.value = el.email;
                            $form.celular.value = el.celular;
                            $form.ncuenta.value = el.ncuenta;
                            $form.tipoCuenta.value = el.tipocuenta;
                            $form.banco.value = el.banco;
                       })
                    })
                 },100)
              }
           }
        }   
    }

const salida = () => {
    Ajax({
        url: "../control/token.php",
        method: "PUT",
        param: {
            token: localStorage.getItem("token"),
            iduser: localStorage.getItem("iduser")
        },
        fSuccess: (resp) => {
            if (resp.code === 200 || resp.code === 203) {
                localStorage.clear();
                ruta("Iniciarsesion.html");
            }
        },
    });
};

function Rol() {
    let $divRol = document.getElementById("drol");
    $divRol.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
    
    Ajax({
        url: "../control/rol.php",
        method: "GET",
        param: undefined,
        fSuccess: (Resp) => {
            if (Resp.code == 200) {
                let opc = ``;
                Resp.data.forEach((el) => {
                    opc += `<option value="${el.IdRolPK}">${el.RolNombre}</option>`;
                });
                $divRol.innerHTML = `<label for="rol">Rol</label><select class="form-select" name="rol" id="rol" required><option value="">Seleccione una</option>${opc}</select>`;
            }
        }
    });
}

function banco() {
    let $div = document.getElementById("dBanco");
    $div.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
    
    Ajax({
        url: "../control/banco.php",
        method: "GET",
        param: undefined,
        fSuccess: (Resp) => {
            if (Resp.code == 200) {
                let opc = ``;
                Resp.data.forEach((el) => {
                    opc += `<option value="${el.IdBancoPK}">${el.BanNombre}</option>`;
                });
                $div.innerHTML = `<label for="banco">Banco</label><select class="form-select" name="banco" id="banco" required><option value="">Seleccione uno</option>${opc}</select>`;
            }
        }
    });
}

function guardarusuario(m) {
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
            } else alert("Error en el registro. " + resp.msg);

        }
    });
}

function listausuario() {
    localStorage.removeItem("id_usuario");
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

function buscarusuario(id, send) {
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
        }
    });
}

function editarusuario(id) {
     //console.log("Clic en Editar el registro id="+id)
    localStorage.setItem("id_usuario", id);
    ruta("actualizarusuario.html?id=" + id);
}

function eliminarusuario(id) {
    let resp = confirm(`¿Desea eliminar el registro del usuario (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/usuario.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code == 200) {
                    listausuario();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}

function guardarproveedor(m) {
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
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}


function listaProveedores() {
    localStorage.removeItem("id_proveedor");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='8' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    
    Ajax({
        url: "../control/proveedores.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            console.log(resp); // Añade este log para ver la respuesta

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



function buscarproveedor(id, send) {
    Ajax({
        url: "../control/proveedores.php",
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

function editarproveedor(id) {
    
    localStorage.setItem("id_proveedor", id);
    ruta("actualizarproveedor.html?id=" + id);
}

function eliminarproveedor(id) {
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

function guardarentregas(m) {
    let datos = {
        entregas: document.getElementById("entregas").value,
        proveedor: document.getElementById("proveedor").value,
        valorcosto: document.getElementById("valorcosto").value,
        cantidad: document.getElementById("cantidad").value,
        fn: document.getElementById("fn").value,
        id_usuario: localStorage.getItem("id_entregas"),
    };

    Ajax({
        url: "../control/entregas.php", 
        method: m, 
        param: datos, 
        fSuccess: (resp) => {
            if (resp.code == 200) {
                alert("El registro fue guardado correctamente");
                ruta("listadeentregas.html");
            } else alert("Error en el registro. " + resp.msg);

        }
    });
}

function listadeentregas() {
    localStorage.removeItem("id_entregas");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='7' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    Ajax({
        url: "../control/entregas.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.Entregas}</td>
                              <td>${el.Proveedor}</td>
                              <td>${el.ValorCosto}</td>
                              <td>${el.Cantidad}</td>
                              <td>${el.Fecha}</td>
                              <td>${el.Acciones}</td>
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
                $tinfo.innerHTML = `<tr><td colspan='7' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        }
    });
}

function buscarentregas(id, send) {
    Ajax({
        url: "../control/entregas.php",
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

function editarentregas(id) {
     //console.log("Clic en Editar el registro id="+id)
    localStorage.setItem("id_entregas", id);
    ruta("actualizarentregas.html?id=" + id);
}

function eliminarentregas(id) {
    let resp = confirm(`¿Desea eliminar el registro del entregas (#${id})?`);
    if (resp) {
        Ajax({
            url: "../control/entregas.php",
            method: "DELETE",
            param: { id },
            fSuccess: (resp) => {
                if (resp.code == 200) {
                    listadeentregas();
                } else {
                    alert("Error en la petición\n" + resp.msg);
                }
            }
        });
    }
}



function relacionarRol(id) {
    localStorage.setItem("id_usuario", id);
    ruta("editarolpermisos.html?id=" + id);
}

const mostrarMenu = async () => {
    let $divmenu = document.getElementById("navbarNav");
    let url = "../control/menu.php";
    let resp = await fetch(url);
    let respText = await resp.text();
    $divmenu.innerHTML = respText;
    validarToken();

    if (location.pathname.includes("registrarusuario")) {
        Rol();
    }
};

document.addEventListener("click", (e) => {
    if (e.target.matches("#salir")) salida();
    if (e.target.matches(".u_usuario")) editarusuario(e.target.dataset.id);
    if (e.target.matches(".d_usuario")) eliminarusuario(e.target.dataset.id);
    if (e.target.matches(".p_usuario")) relacionarRol(e.target.dataset.id);
    if (e.target.matches(".u_proveedor")) editarproveedor(e.target.dataset.id);
    if (e.target.matches(".d_proveedor")) eliminarproveedor(e.target.dataset.id);
    if (e.target.matches(".u_entregas")) editarentregas(e.target.dataset.id);
    if (e.target.matches(".d_entregas")) eliminarentregas(e.target.dataset.id);
});

document.addEventListener("submit", (e) => {
    e.preventDefault();
    if (e.target.matches("#form-usuario")) guardarusuario("POST");
    if (e.target.matches("#form-act_usuario")) guardarusuario("PUT");
    if (e.target.matches("#form-proveedor")) guardarproveedor("POST");
    if (e.target.matches("#form-act_proveedor")) guardarproveedor("PUT");
    if (e.target.matches("#form-entregas")) guardarentregas("POST");
    if (e.target.matches("#form-act_entregas")) guardarentregas("PUT");
});


function loguear() {
    let user = document.getElementById("user").value;
    let pass = document.getElementById("pass").value;

    if (user === "damg1312@hotmail.com" && pass === "cafDB1109") {
        window.location = "principal.html";
    } else {
        alert("Datos incorrectos");
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.navbar-toggler');
    const navList = document.querySelector('.navbar-collapse');

    if (hamburger && navList) {
        hamburger.addEventListener('click', () => {
            navList.classList.toggle('show');
        });
    }
    mostrarMenu();
})

if (location.pathname.includes("listausuario")) listausuario()

if (location.pathname.includes("listaproveedores")) listaProveedores()

if (location.pathname.includes("listadeentregas")) listadeentregas()

if (location.pathname.includes("editarolpermisos")) {
    Rol()
    setTimeout(() => {
        let $form = document.getElementById("datos_user"), info="", rol="";
        buscarusuario(localStorage.getItem("id_usuario"), (resp) => {
            resp.forEach((el) => {
                info=`
                <blockquote class="blockquote mb-0">
                <p>${el.nombre} ${el.apellidos}</p>
                <footer class="blockquote-footer">${el.email}</footer>
                </blockquote>
                `;                
                document.getElementById("rol").value=el.idrol
            })
            $form.innerHTML=info;
        })
        },100)
    }
    


    


    


    


