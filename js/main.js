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
        // funciones del registrar usuario
        Rol();
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

function Rol(){
    let $divRol = document.getElementById("drol");
    $divRol.innerHTML= `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
    //console.log($divRol)
    Ajax({
       url: "../control/rol.php",
       method: "GET", 
       param: undefined, 
       fSuccess:(Resp)=>{
          //console.log(Resp)
          if(Resp.code==200){            
             let opc=``;
             //console.log(Resp.data)
             Resp.data.map((el) => {
                opc+=`<option value="${el.IdRolPK}">${el.RolNombre}</option>`;               
                //console.log(el)
             });
             $divRol.innerHTML=  `<label for="rol" >Rol</label><select class="form-select" name="rol" id="rol" required><option value="">Seleccione una</option>${opc}</select>`;
          }                     
       }
    })
 }

 function guardarUsuario(){
    
 }

const mostrarMenu = async ()=>{
    let $divmenu = document.getElementById("navbarNav");
    let url = "../control/menu.php"
    let resp = await fetch(url);
    let respText = await resp.text();
    // console.log(respJson);
    $divmenu.innerHTML=respText;
    validarToken

    // funciones del registrar usuario
    Rol();
}

document.addEventListener("DOMContentLoaded", (e) => {
    mostrarMenu();
});

document.addEventListener("click", (e) => {
    if (e.target.matches("#salir")) salida();
    if (e.target.matches("btnguardar")){
        e.preventDefault();
        guardarUsuario()
    }
});

function loguear() {
    let user = document.getElementById("user").value;
    let pass = document.getElementById("pass").value;

    if (user === "damg1312@hotmail.com" && pass === "cafDB1109") {
        window.location = "principal.html";
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



    
       
    

    
       
    
















   











    
       
    

    
       
    
















   









