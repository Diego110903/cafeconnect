//IMPORTACION DE MODULOS EN JAVASCRIPT
import {Ajax, Rol, banco, ruta} from "./auxiliares.js"
import {guardarUsuario, buscarusuario, editarusuario, listaUsuario, eliminarusuario} from "./usuarios.js"
import {buscarproveedor, cargarProveedores, editarproveedor, eliminarproveedor, guardarproveedor, listaProveedores} from "./proveedores.js"
 

// FUNCIONES DE MAIN.JS
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

           if (location.pathname.includes("listausuario")) listaUsuario()

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

        if (location.pathname.includes("registrarentregas") | location.pathname.includes("actualizarentregas")) {
            cargarProveedores()
            if (location.pathname.includes("actualizarentregas")) {
                setTimeout(() => {
                    let $form = document.getElementById("form-act_entregas");
                    buscarentregas(localStorage.getItem("id_entregas"), (resp) => {
                        resp.forEach((el) => {
                            $form.entregas.value = el.identregas;
                            $form.proveedor.value = el.idproveedor
                            $form.valorcosto.value = el.valorcosto;
                            $form.cantidad.value = el.cantidad;
                            $form.fecha.value = el.fecha;
                       })
                    })
                 },100)
              }
           }
        

           if (location.pathname.includes("registrarproveedor") | location.pathname.includes("actualizarproveedor")) {
            banco()
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
        }
    })
}

function guardarentregas(m) {
    let datos = {
        proveedor: document.getElementById("proveedor").value,
        valorcosto: document.getElementById("valorcosto").value,
        cantidad: document.getElementById("cantidad").value,
        fecha: document.getElementById("fecha").value,
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
    $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'><div class="spinner-border text-black" role="status"><span class="sr-only">Cargando...</span></div><br>Procesando...</td></tr>`;
    Ajax({
        url: "../control/entregas.php",
        method: "GET",
        param: undefined,
        fSuccess: (resp) => {
            if (resp.code == 200) {
                let data = resp.data;
                item = "";
                data.forEach((el) => {
                    item += `<tr>
                              <th scope='row'>${el.id}</th>
                              <td>${el.nombre}</td>
                              <td>${el.valorcosto}</td>
                              <td>${el.cantidad}</td>
                              <td>${el.fecha}</td>
                              <td>
                                <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary fa fa-edit u_entregas" title='Editar' data-id='7'></button>
                                <button type="button" class="btn btn-outline-danger fa fa-trash d_entregas" title='Eliminar' data-id='${el.id}'></button>
                                </div>
                              </td>
                            </tr>`;
                });
                $tinfo.innerHTML = item;
            } else {
                $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'>Error en la petición <b>${resp.msg}</b></td></tr>`;
            }
        },
        fError: (err) => {
            $tinfo.innerHTML = `<tr><td colspan='6' class='text-center'>Error al cargar los datos</td></tr>`;
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
    localStorage.setItem("id_entregas", id);
    ruta("actualizarentregas.html?id=" + id);
}

function eliminarentregas(id) {
    let resp = confirm(`¿Desea eliminar el registro de la entrega (#${id})?`);
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
    if (e.target.matches("#form-usuario")) guardarUsuario("POST");
    if (e.target.matches("#form-act_usuario")) guardarUsuario("PUT");
    if (e.target.matches("#form-proveedor")) guardarproveedor("POST");
    if (e.target.matches("#form-act_proveedor")) guardarproveedor("PUT");
    if (e.target.matches("#form-entregas")) guardarentregas("POST");
    if (e.target.matches("#form-act_entregas")) guardarentregas("PUT");
})

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
    
   
    
   
    

    


    

    

    
    
    
    

    
    


    


    


    


