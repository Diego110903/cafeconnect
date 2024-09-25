import { Ajax, Rol, banco, ruta, medioPago } from "./auxiliares.js";
import { guardarUsuario, buscarusuario, editarusuario, listaUsuario, eliminarusuario } from "./usuarios.js";
import { buscarproveedor, cargarProveedores, editarproveedor, eliminarproveedor, guardarproveedor, listaProveedores } from "./proveedores.js";
import { guardarentregas, buscarentregas, editarentregas, listadeentregas, eliminarentregas } from "./entregas.js";
import { guardarproducto, buscarproducto, editarproducto, eliminarproducto, listaproducto } from "./productos.js";
import { guardaritems, buscaritems, editaritems, listaitems, eliminaritems} from "./items.js";
import { guardarfactura, eliminarfactura, listafacturas, buscarfactura,  } from "./facturas.js";


function validarToken() {
    if (localStorage.getItem("token")) {
        const div_info_user = document.getElementById("info_user");
        if (div_info_user) {
            div_info_user.innerHTML = `${localStorage.getItem("user")}`;
        }

        if (location.pathname.includes("registrarusuario") || location.pathname.includes("actualizarusuario")) {
            Rol();
            if (location.pathname.includes("actualizarusuario")) {
                buscarusuario(localStorage.getItem("id_usuario"), (resp) => {
                    let $form = document.getElementById("form-act_usuario");
                    if ($form) {
                        resp.forEach((el) => {
                            $form.id_usuario.value = el.id;
                            $form.nombre.value = el.nombre;
                            $form.apellidos.value = el.apellidos;
                            $form.email.value = el.email;
                            $form.rol.value = el.rol;
                        });
                    }
                });
            }
        }

        if (location.pathname.includes("listausuario")) listaUsuario();
        if (location.pathname.includes("listaproveedores")) listaProveedores();
        if (location.pathname.includes("listadeentregas")) listadeentregas();
        if (location.pathname.includes("listaproducto")) listaproducto();
        if (location.pathname.includes("listaitems")) listaitems();
        if (location.pathname.includes("listafacturas")) listafacturas();
        if (location.pathname.includes("editarolpermisos")) {
            Rol();
            buscarusuario(localStorage.getItem("id_usuario"), (resp) => {
                let $form = document.getElementById("datos_user");
                if ($form) {
                    let info = "";
                    resp.forEach((el) => {
                        info = `
                            <blockquote class="blockquote mb-0">
                            <p>${el.nombre} ${el.apellidos}</p>
                            <footer class="blockquote-footer">${el.email}</footer>
                            </blockquote>
                        `;
                        document.getElementById("rol").value = el.idrol;
                    });
                    $form.innerHTML = info;
                }
            });
        }
    
        if (location.pathname.includes("registrarentregas") || location.pathname.includes("actualizarentregas")) {
            cargarProveedores();
            if (location.pathname.includes("actualizarentregas")) {
                buscarentregas(localStorage.getItem("id_entregas"), (resp) => {
                    let $form = document.getElementById("form-act_entregas");
                    if ($form) {
                        resp.forEach((el) => {
                            $form.id_entregas.value = el.identregas;
                            $form.valorcosto.value = el.valorcosto;
                            $form.cantidad.value = el.cantidad;
                            $form.fecha.value = el.fecha;
                            $form.proveedor.value = el.proveedor;
                        });
                    }
                });
            }
        }

  
        if (location.pathname.includes("registrafacturas") || location.pathname.includes("actualizarfacturas")) {
            cargarMediopago();
            if (location.pathname.includes("actualizarfacturas")) {
                buscarfactura(localStorage.getItem("id_factura"), (resp) => {
                    let $form = document.getElementById("form-act_facturas");
                    if ($form) {
                        resp.forEach((el) => {
                            $form.id_factura.value = el.idfactura;
                            $form.fecha.value = el.fecha;
                            $form.hora.value = el.hora;
                            $form.valortotal.value = el.valortotal;
                            $form.mediopago.value = el.mediopago;
                        });
                    }
                });
            }
        }

        if (location.pathname.includes("registraritems") || location.pathname.includes("actualizaritems")) {
    
            
            if (location.pathname.includes("actualizaritems")) {
                buscaritems(localStorage.getItem("id_item"), (resp) => {
                    let $form = document.getElementById("form-act_items");
                    if ($form) {
                        resp.forEach((el) => {
                            $form.id_item.value = el.iditem;
                            $form.id_producto.value = el.idproducto;
                            $form.id_factura.value = el.idfactura;
                            $form.cantidad.value = el.cantidad;
                            $form.valor_unitario.value = el.valorunitario;
                            $form.valor_total.value = el.valortotal;
                        });
                    }
                });
            }
        }
        

        if (location.pathname.includes("registrarproducto") || location.pathname.includes("actualizarproducto")) {
            if (location.pathname.includes("actualizarproducto")) {
                buscarproducto(localStorage.getItem("id_producto"), (resp) => {
                    let $form = document.getElementById("form-act_producto");
                    if ($form) {
                        if (resp.length > 0) {
                            resp.forEach((el) => {
                                $form.id_producto.value = el.id_producto; // Verifica que la propiedad sea correcta
                                $form.nombre.value = el.nombre;
                                $form.presentacion.value = el.presentacion;
                                $form.valorunitario.value = el.valorunitario; // Asegúrate de que este campo exista
                                $form.minimostock.value = el.minimostock;
                            });
                        } else {
                            console.error("No se encontraron datos para el producto.");
                        }
                    }
                });
            }
        }       

        if (location.pathname.includes("registrarproveedor") || location.pathname.includes("actualizarproveedor")) {
            banco();
            if (location.pathname.includes("actualizarproveedor")) {
                buscarproveedor(localStorage.getItem("id_proveedor"), (resp) => {
                    let $form = document.getElementById("form-act_proveedor");
                    if ($form) {
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
                        });
                    }
                });
            }
        }

        if (location.pathname.includes("registrofacturas")) {
            medioPago()
            let $fecha=document.getElementById("fecha"), $hora=document.getElementById("hora"), $vf=document.getElementById("valorfactura")
            $fecha.innerHTML= new Date().toLocaleDateString()
            $hora.innerHTML= new Date().toLocaleTimeString()
            $vf.innerHTML= "0,0"

        }

    }
}

if (location.pathname.includes("registrofacturas")) {
    medioPago()
    let $fecha=document.getElementById("fecha"), $hora=document.getElementById("hora"), $vf=document.getElementById("valorfactura")
    $fecha.innerHTML= new Date().toLocaleDateString()
    $hora.innerHTML= new Date().toLocaleTimeString()
    $vf.innerHTML= "0,0"

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

function relacionarRol(id) {
    localStorage.setItem("id_usuario", id);
    ruta("editarolpermisos.html?id=" + id);
}

const mostrarMenu = async () => {
    let $divmenu = document.getElementById("navbarNav");
    if ($divmenu) {
        try {
            let url = "../control/menu.php";
            let resp = await fetch(url);
            let respText = await resp.text();
            $divmenu.innerHTML = respText;
        } catch (error) {
            alert("Error al cargar el menú");
        }
    }
    validarToken();
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
    if (e.target.matches(".u_producto")) editarproducto(e.target.dataset.id);
    if (e.target.matches(".d_producto")) eliminarproducto(e.target.dataset.id);
    if (e.target.matches(".u_items")) editaritems(e.target.dataset.id);
    if (e.target.matches(".d_items")) eliminaritems(e.target.dataset.id);
    if (e.target.matches(".d_factura")) eliminarfactura(e.target.dataset.id);
   

    if (e.target.matches("#btncancelar")) {
        window.location.href = "principal.html";
    }
});

document.addEventListener("submit", (e) => {
    e.preventDefault();
    if (e.target.matches("#form-usuario")) guardarUsuario("POST");
    if (e.target.matches("#form-act_usuario")) guardarUsuario("PUT");
    if (e.target.matches("#form-proveedor")) guardarproveedor("POST");
    if (e.target.matches("#form-act_proveedor")) guardarproveedor("PUT");
    if (e.target.matches("#form-entregas")) guardarentregas("POST");
    if (e.target.matches("#form-act_entregas")) guardarentregas("PUT");
    if (e.target.matches("#form-producto")) guardarproducto("POST");
    if (e.target.matches("#form-act_items")) guardaritems("PUT");
    if (e.target.matches("#form-items")) guardaritems("POST");
    if (e.target.matches("#form-factura")) guardarfactura("POST");
   
});


document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.navbar-toggler');
    const navList = document.querySelector('.navbar-collapse');

    if (hamburger && navList) {
        hamburger.addEventListener('click', () => {
            navList.classList.toggle('show');
        });
    }
    mostrarMenu();
});

    
   
    

    

    


    

    

    
    
    
    

    
    


    


    


    



    
   
    
   
    

    


    

    

    
    
    
    

    
    


    


    


    


    
   
    

    

    


    

    

    
    
    
    

    
    


    


    


    



    
   
    
   
    

    


    

    

    
    
    
    

    
    


    


    


    


