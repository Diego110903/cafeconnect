export async function Ajax (info){
    let {url, method, param, fSuccess} = info
    if(param !== undefined && method==="GET") url += "?"+ new URLSearchParams(param)
    if (method === "GET") method={method,headers: {'Content-Type':'application/json'}}    
    if (method === "POST" || method === "PUT" || method === "DELETE") method={method,headers: {'Content-Type':'application/json'},body: JSON.stringify(param)}
    
    try{
        //console.log(url,method)
        let resp = await fetch(url,method);
        if(!resp.ok) throw {status: resp.status, msg: resp.statusText};
        let respJson =  await resp.json();  
        fSuccess(respJson)
    }catch(e){
       fSuccess({code: e.status, msg: e.msg})
    }
}

export function ruta(url="",blank = undefined){
    //if(blank===undefined) {window.location.href = url} else {window.open(url)}
    (blank===undefined) ? window.location.href = url : window.open(url)
}

export function Rol() {
    let $divRol = document.getElementById("drol");
    $divRol.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
    
    Ajax({
        url: "../control/rol.php",
        method: "GET",
        param: undefined,
        fSuccess: (Resp) => {
            if (Resp.code == 200) {
                let opc = ``;
                Resp.data.forEach((el) => {
                    opc += `<option value="${el.IdRolPK}">${el.RolNombre}</option>`;
                });
                $divRol.innerHTML = `<label for="rol">Rol</label><select class="form-select" name="rol" id="rol" required><option value="">Seleccione una</option>${opc}</select>`;
            }
        }
    });
}

export function banco() {
    let $div = document.getElementById("dBanco");
    $div.innerHTML = `<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`;
    
    Ajax({
        url: "../control/banco.php",
        method: "GET",
        param: undefined,
        fSuccess: (Resp) => {
            if (Resp.code == 200) {
                let opc = ``;
                Resp.data.forEach((el) => {
                    opc += `<option value="${el.IdBancoPK}">${el.BanNombre}</option>`;
                });
                $div.innerHTML = `<label for="banco">Banco</label><select class="form-select" name="banco" id="banco" required><option value="">Seleccione uno</option>${opc}</select>`;
            }
        }
    });
}