let $divMsg = document.querySelector("#msgLogin");

const enviarLogin = async (url = "", method = "", param = undefined) => {
    if (method === "POST") {
        method = { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(param) };
    }

    try {
        $divMsg.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
        let resp = await fetch(url, method);
        if (!resp.ok) throw { status: resp.status, msg: resp.statusText };
        let respJson = await resp.json();
        validarLogin(respJson);
    } catch (e) {
        $divMsg.innerHTML = `<b class='text-danger'>Error: ${e.msg || e.message}</b>`;
    }
}

const validarLogin = (info) => {
    if (info.code === 200) {
        localStorage.clear();
        localStorage.setItem("token", info.idToken);
        localStorage.setItem("iduser", info.idUser);
        localStorage.setItem("user", info.Usuario);
        location.href = "menuprincipal.html?token=" + info.idToken;
    } else {
        $divMsg.innerHTML = `<b class='text-danger'>${info.msg}</b>`;
        setTimeout(() => {
            $divMsg.innerHTML = "";
        }, 4000);
    }
};

let form = document.getElementById("form_login");

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let user = document.getElementById("user").value;
    let pass = md5(document.getElementById("pass").value);
    let param = { user, pass };

    if (user !== "" && pass !== "") {
        enviarLogin("../control/login.php", "POST", param);
    } else {
        $divMsg.innerHTML = `<b class='text-danger'>Usuario y contraseña no pueden estar vacíos</b>`;
    }
});






    
    








    
    






    
    








    
    