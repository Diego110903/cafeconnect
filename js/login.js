

    let form = document.getElementById("form_login")

    form.addEventListener('submit', (e)=>{
        e.preventDefault()
        // alert("estoy aqui... ok")

        let user = document.getElementById("user").value
        let pass = document.getElementById("pass").value
        let var_json = JSON.stringify({usu: user,clave: pass})
        //  console.log(json)
        //  console.log(JSON.parse(json))


        if(user!=="" && pass!==""){
             //  alert("estoy aqui... ok")
            let info = {
                method: "POST",
                headers: {'content-type':'application/json'},
                body: var_json
           
            }
            fetch("../control/login.php",info)
            // .then((resp)=>{div.innerHTML += resp.json()})
    
        }
        /*if(e.target === $btniniciar){
            if($pass.value !== "" && $user.value !==""){
                e.preventDefault();
                window.location.href="menu.html"
            }
        }*/
        return false    
    })






