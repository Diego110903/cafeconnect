// function ruta(url="",blank = undefined){
    // if(blank===undefined) {window.location.href = url} // else {window.open(url)}
    //  (blank===undefined) ? window.location.href = url : window.open(url)
// }

function loguear()
{
 let user=document.getElementById("user").value;
 let pass=document.getElementById("pass").value;

if(user=="damg1312@hotmail.com" && pass=="12345")

{ 
   window.location= "menuprincipal.html";
}
else
 { 
    alert("datos incorrectos");
 
} }

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.navbar-toggler');
    const navList = document.querySelector('.navbar-collapse');

    if (hamburger && navList) {
        hamburger.addEventListener('click', function() {
            navList.classList.toggle('show');
        });
    } else {
        // console.error('No se encontraron elementos .navbar-toggler o .navbar-collapse en el DOM.');
    }
});



document.addEventListener('DOMContentLoaded', function() {
    function validarToken() {
        console.log(localStorage);
        var divInfoUser = document.getElementById("info_user");
        
        if (localStorage.getItem("token")) {
            if (divInfoUser) {
                divInfoUser.innerHTML = `${localStorage.getItem("user")}`;
            } else {
                // console.error('El elemento #info_user no existe en el DOM.');
            }
        } else {
            salir("login.html");
        }
    }

    const salir = (url)=>{
        
    }
    
    // Ejecutar la función validarToken después de definirla
    validarToken();
});

function cancelar() {
    location.href = 'Iniciarsesion.html';
}




function ruta(url)
    {location.href=url}

$("#btn-iniciar").on("click",()=>{
    //$("#form_login").submit()

})

    $("#btnnuevo").on("click",()=>{
    ruta("olvidotucontrasena.html")
    })


    
  

//cuando en js de la pg este listose aplica el siguiente codigo 

$(()=>{
    //let  titulo = $(".div-inicio").html()
   // alert(titulo)
//    $("#btn-iniciar").html("iniciar sesion").css({'color':'black', 'font-size':'1.3em'})
//    $("#btnnuevo").html("olvido tu contrasena").css({'color':'black', 'font-size':'1.3em'})
//$("#btn-iniciar").remove().fadeOut(3000)animacion
    // $("#btn-iniciar").slideUp(300).delay(800).fadeIn(400);animacion
    //  $(".logo-container").hide(2000).delay(1000).show(2000)
    // $(".logo-container").slideUp(3000).delay(800).fadeIn(400);animacion
    //$(".logo-container").hide(3000)
})


 
//USER="+nom_usu+"&"+pass_usu

    //$(".botoninicio").hide()

    
       
    

    
       
    
















   