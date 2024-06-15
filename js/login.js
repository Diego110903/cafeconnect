let divMsg = document.querySelector("#msgLogin")

const enviarLogin = async (url="", method = "", param = undefined)=>{
    // if(param !== undefined && method==="GET") url += "?"+ new URLSearchParams(param)
    // if (method === "GET") method={method,headers: {'content-type':'application/json'}}
    if (method === "POST") method={method,headers: {'content-type':'application/json'},body: JSON.stringify(param)}
    // if (method === "PUT") method={method,headers: {'content-type':'application/json'},body: JSON.stringify(param)}
    // if (method === "DELETE") method={method,headers: {'content-type':'application/json'},body: JSON.stringify(param)}


    divMsg.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`
    fetch(url,method)
         .then(response =>response.json())
         .then(data=> validarLogin(data))
         .catch(e => console.log(e))
    }
    const validarLogin = (info)=>{
        (info.code===200) ? window.location.href="menuprincipal.html" : divMsg.innerHTML =`<b class='text-danger'>${info.msg}</b>`
        
           
    }

    let form = document.getElementById("form_login")

    form.addEventListener('submit', (e)=>{
        e.preventDefault()
        let user = document.getElementById("user").value
        let pass = md5(document.getElementById("pass").value)
        let param = {user, pass}
        // console.log(param)
        // console.log(JSON.stringify(param))
       
        if(user!=="" && pass!==""){
            let method = "POST"
            // fetch('../control/login.php', {method:'POST' ,body: JSON.stringify(param)})
            // fetch('../control/login.php?'+ new URLSearchParams(param), {method: 'GET'})
            enviarLogin("../control/login.php",method,param)
            
        }
    })

    








