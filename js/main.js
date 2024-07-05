// function ruta(url="",blank = undefined){
    // if(blank===undefined) {window.location.href = url} // else {window.open(url)}
    //  (blank===undefined) ? window.location.href = url : window.open(url)
// }

function loguear()
{
 let user=document.getElementById("user").value;
 let pass=document.getElementById("pass").value;

if(user=="damg1312@outlook.com" && pass=="12345")

{ 
   window.location= "menuprincipal.html";
}
else
 { 
    alert("datos incorrectos");
 
} }

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navList = document.getElementById('nav-list');

    hamburger.addEventListener('click', function() {
        navList.classList.toggle('show');
    });
});










document.addEventListener('DOMContentLoaded', function() {
    function validarToken() {
        console.log(localStorage);
        var divInfoUser = document.getElementById("info_user");
        
        if (localStorage.getItem("token")) {
            if (divInfoUser) {
                divInfoUser.innerHTML = `${localStorage.getItem("user")}`;
            } else {
                console.error('El elemento #info_user no existe en el DOM.');
            }
        } else {
            ruta("login.html");
        }
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
    ruta("olvidotucontraseña.html")
    })


    
    /*$("#form_login").on("submit", (e)=>{
    e.preventDefault();
       // alert("ON submit del formulario");
        let nom_usu=$("#correo").val()
        let pass_usu=$("#contrasena").val()
        // let data = $("#form_login").serialize()
        if(nom_usu!="" && pass_usu!= ""){
            $("#contrasena").val(md5(pass_usu))
            let info = {
                "user":nom_usu,
                "pass":md5(pass_usu)
            }
            // alert("ok --- inicia ajax")
            // aplicando tecnica ajax
            $.ajax({
                data: info,
                url: "../control/login.php",
                type: "GET",
                beforeSend:()=>{
                    console.log("procesando la peticion...")
                }
            }).done((resp)=>{
                $("#div-msg1").html(resp)
            })
            // fin ajax

          //Aplicando el metodo de Fetch
        const div = document.getElementById('div-msg2')
        fetch("../control/login.php?user="+nom_usu+"&pass="+md5(pass_usu),info).then((resp)=>{div.innerHTML += resp.json()})   
        //Fin Fetch
        //fetch("../control/login.php",info).then((resp)=>resp.json()).then((dataj)=>{
            //div.innerHTML += dataj[0]
        //})


            // alert("Datos envia\n\n"+data)
            // return false
    }else{
        alert("ingrese la informacion requerida")
        //alert("Datos envia\n\nUsuario: "+nom_usu+"\ncontasena: "+pass_usu)
        $("#correo").focus()
        
        }
            
        // alert("Datos envia\n\nUsuario: "+nom_usu+"\ncontasena: "+pass_usu)
        // ruta("../control/login.php")
        //alert("ok --- inicia ajax")
        
        //ajax
})*/

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

    
       
    

    
       
    
















   