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
        const div_info_user = document.getElementById("info-user");
        if (div_info_user) {
            div_info_user.innerHTML = `${localStorage.getItem("user")}`;
        } else {
            // console.error('Elemento #info-user no encontrado.');
        }
        // funciones de registrar usuario
        if(location.pathname.includes("registrarusuario")){
        Rol();
    }
    // funciones de lista usuario
    if(location.pathname.includes("listausuario")){
    ListaUsuario()
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

    // Envía los datos al servidor
    Ajax({
        url: "../control/usuario.php",
        method: "POST",
        param: datos,
        fSuccess: (resp) => {
            console.log(resp);
            if (resp.code == 200) {
                alert("El registro fue guardado correctamente");
            } else {
                alert("Error en el registro. " + resp.msg);
            }
        }
    });
}





const mostrarMenu = async () => {
    let $divmenu = document.getElementById("navbarNav");
    let url = "../control/menu.php";
    let resp = await fetch(url);
    let respText = await resp.text();
    $divmenu.innerHTML = respText;
    validarToken(); // Se agregó la llamada correcta a validarToken

    // funciones del registrar usuario
    Rol();
};

document.addEventListener("DOMContentLoaded", (e) => {
    mostrarMenu();
});

document.addEventListener("click", (e) => {
    if (e.target.matches("#salir")) salida();
    if (e.target.matches("#btnguardar")) {
        // e.preventDefault();
        guardarusuario();
    }
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
    } else {
        // console.error('No se encontraron elementos .navbar-toggler o .navbar-collapse en el DOM.');
    }
});

$("#btn-iniciar").on("click", () => {
    // Código para el botón iniciar
});

$("#btnnuevo").on("click", () => {
    ruta("olvidotucontrasena.html");
});

$(document).ready(() => {
    // Tu código jQuery aquí, si usas jQuery
});




    
       
    

    
       
    
















   











    
       
    

    
       
    
















   












    
       
    

    
       
    
















   











    
       
    

    
       
    
















   









