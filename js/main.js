
//document.getElementsByName("btnnuevo").addEventlistener({})

function ruta(url){
    location.href=url
}

$("#btn-iniciar").on("click",()=>{
    $("#form_login").submit()
})

    $("#btnnuevo").on("click",()=>{
    ruta("OlvidotuContrasena.html")
    })

    $("#form_login").on("submit",()=>{
       // alert("ON submit del formulario");
       let data = $("#form_login").serialize()
        alert("Datos envia/n/n"+data)
        ruta("../control/login.php")
        return false
        //ajax
})

//cuando en js de la pg este listose aplica el siguiente codigo 

$(()=>{
    //let  titulo = $(".div-inicio").html()
   // alert(titulo)
   $("#btn-iniciar").html("ingresar sesion").css({'color':'black', 'font-size':'1.3em'})
   $("#btnnuevo").html("olvido tu contrasena").css({'color':'black', 'font-size':'1.3em'})
//$("#btn-iniciar").remove().fadeOut(3000)animacion
    //$("#btn-iniciar").slideUp(300).delay(800).fadeIn(400);animacion
    $(".logo-container").hide(4000).delay(1000).show(2000)
    //$(".logo-container").slideUp(300).delay(800).fadeIn(400);animacion
    //$(".logo-container").hide(3000)
})





    //$(".botoninicio").hide()

    
       
    