
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
    //$(".botoninicio").hide()

    
       
    