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

        if (location.pathname.includes("registrarusuario")) {
            Rol();
        }

        if (location.pathname.includes("listausuario")) {
            listausuario();
        }

    } else {
        salida();
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

function guardarusuario() {
    let datos = {
        nombre: document.getElementById("nombre").value,
        apellidos: document.getElementById("apellidos").value,
        email: document.getElementById("email").value,
        rol: document.getElementById("rol").value,
        password: document.getElementById("password").value,
        confirmar: document.getElementById("confirmar").value,
    };

    Ajax({
        url: "../control/usuario.php", 
        method: "POST", 
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
    $tinfo.innerHTML= `<tr><td colspan='6' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only"></span></div><br>Procesando...</td></tr>`;
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

function editarusuario(id) {
    console.log("clic en editar el registro id=" + id);
    // Implementa la lógica para editar el usuario
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

document.addEventListener("DOMContentLoaded", () => {
    mostrarMenu();
});

document.addEventListener("click", (e) => {
    if (e.target.matches("#salir")) salida();
    if (e.target.matches(".u_usuario")) editarusuario(e.target.dataset.id);
    if (e.target.matches(".d_usuario")) eliminarusuario(e.target.dataset.id);
    if (e.target.matches("#btnguardar")) guardarusuario();
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
});










    
       
    

    
       
    
















   











    
       
    

    
       
    
















   












    
       
    

    
       
    
















   











    
       
    

    
       
    
















   

















    
       
    

    
       
    
















   











    
       
    

    
       
    
















   












    
       
    

    
       
    
















   











    
       
    

    
       
    
















   















    
       
    

    
       
    
















   











    
       
    

    
       
    
















   












    
       
    

    
       
    
















   











    
       
    

    
       
    
















   









