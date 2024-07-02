let $divMsg = document.querySelector("#msgLogin");

const enviarLogin = async (url = "", method = "", param = undefined) => {
    let options = {
        method: method,
        headers: { 'Content-Type': 'application/json' }
    };

    if (param !== undefined && (method === "POST" || method === "PUT" || method === "DELETE")) {
        options.body = JSON.stringify(param);
    }

    try {
        $divMsg.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
        let resp = await fetch(url, options);

        // Manejar errores HTTP
        if (!resp.ok) {
            throw new Error(`HTTP error! status: ${resp.status}`);
        }

        let respJSON = await resp.json();
        validarLogin(respJSON);
    } catch (e) {
        $divMsg.innerHTML = `<b class='text-danger'>Error: ${e.message}</b>`;
    } finally {
        // Remove spinner regardless of success or failure
        setTimeout(() => {
            $divMsg.innerHTML = ''; // Clear spinner after a brief delay
        }, 3000);
    }
};

const validarLogin = (info) => {
    if (info.code === 200) {
        console.log(info);
        localStorage.clear();
        localStorage.setItem("token",info.idToken)
        localStorage.setItem("iduser",info.idUser)
        localStorage.setItem("user",info.Usuario)
        // console.log(localStorage);
        location.href = "menuprincipal.html?token=" + info.idtoken;
    } else {
        $divMsg.innerHTML = `<b class='text-danger'>${info.msg}</b>`;
    }
};

let form = document.getElementById("form_login");

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let user = document.getElementById("user").value;
    let pass = md5(document.getElementById("pass").value);
    let param = { user, pass };
    // console.log(param)
    // console.log(JSON.stringify(param))

    if (user !== "" && pass !== "") {
        // let method = "POST"
        // fetch('../control/login.php', {method: 'post',body: JSON.stringify(param)})
    // fetch('../control/login.php?'+ new URLSearchParams(params), {method: 'GET'})
        enviarLogin("../control/login.php", "POST", param);
    } else {
        $divMsg.innerHTML = `<b class='text-danger'>Usuario y contraseña no pueden estar vacíos</b>`;
    }
});





    
    








    
    