function ruta(url, blank = undefined) {
    (blank === undefined) ? window.location.href = url : window.open(url);
}

const Ajax = async (info) => {
    let { url, method, param, fSuccess } = info;
    if (param !== undefined && method === "GET") url += "?" + new URLSearchParams(param);
    if (method === "GET") method = { method, headers: { 'Content-Type': 'application/json' } };
    if (method === "POST" || method === "PUT" || method === "DELETE") 
        method = { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(param) };

    try {
        let resp = await fetch(url, method);
        if (!resp.ok) throw { status: resp.status, msg: resp.statusText };
        let respJSON = await resp.json();
        fSuccess(respJSON);
    } catch (e) {
        fSuccess({ code: e.status, msg: e.msg });
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

document.addEventListener("DOMContentLoaded", () => {
    validarToken();
});

document.addEventListener("click", (e) => {
    if (e.target.matches("#salir")) salida();
});

function loguear() {
    let user = document.getElementById("user").value;
    let pass = document.getElementById("pass").value;

    if (user === "damg1312@hotmail.com" && pass === "cafDB1109") {
        window.location = "menuprincipal.html";
    } else {
        alert("datos incorrectos");
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


    
       
    

    
       
    
















   









