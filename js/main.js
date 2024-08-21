function ruta(url = "", blank = undefined) {
    blank === undefined ? window.location.href = url : window.open(url);
}

const Ajax = async (info) => {
    let { url, method, param, fSuccess } = info;

    if (param !== undefined && method === "GET") {
        url += "?" + new URLSearchParams(param);
    }

    let options = { method, headers: { 'Content-Type': 'application/json' } };

    if (method !== "GET") {
        options.body = JSON.stringify(param);
    }

    try {
        let resp = await fetch(url, options);
        if (!resp.ok) throw { status: resp.status, msg: resp.statusText };
        let respJSON = await resp.json();
        fSuccess(respJSON);
    } catch (e) {
        fSuccess({ code: e.status || 500, msg: e.msg || "Error desconocido" });
    }
};

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

function listaProveedores(){
    localStorage.removeItem("id_usuario");
    let $tinfo = document.getElementById("tinfo"), item = "";
    $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
    Ajax({
        url: "../control/proveedores.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                resp.data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el. IdProvedorPK}</th>
                              <td>${el.ProvIdentificacion}</td>
                              <td>${el.ProvNombre}</td>
                              <td>${el.ProvEmail}</td>
                              <td>${el.ProvCelular}</td>
                              <td>
                                ${el.ProvNcuenta}
                                <br><small class='text-primary'>${el.ProvTipoCuenta}</small> / <small class='text-success'>${el.BanNombre}</small>
                              </td>
                              <td> 
                                <div class="btn-group" role="group">
                                  <button type="button" class="btn btn-outline-primary fa fa-edit u_usuario" title='Editar' data-id='${el.id}'></button>
                                  <button type="button" class="btn btn-outline-danger fa fa-trash d_usuario" title='Eliminar' data-id='${el.id}'></button>
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
    if (e.target.matches("#btnguardar")) guardarusuario();
});

document.addEventListener("submit", (e) => {
    e.preventDefault();
    if (e.target.matches("#form-usuario")) guardarusuario("POST");
    if (e.target.matches("#form-act_usuario")) guardarusuario("PUT");
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
    