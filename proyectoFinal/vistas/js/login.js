const regexMail = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/g;
document.addEventListener("DOMContentLoaded", () => {

    document.getElementById("txtCorreo").onkeyup = e => {
        if (e.code == "Tab") return;
        let txt = e.target;
        if (txt.value.trim().match(regexMail)) {
            txt.setCustomValidity("");
            txt.classList.add("valido");
            txt.classList.remove("novalido");
        } else {
            txt.setCustomValidity("Campo no válido");
            txt.classList.remove("valido");
            txt.classList.add("novalido");
        }
    }

    document.getElementById("txtPassword").onkeyup = e => {
        
        revisarControl(e, 8, 50);
    }

});



function revisarControl(e, min, max) {
    debugger;
    if (e.code == "Tab") return;
    txt = e.target;
    txt.setCustomValidity("");
    txt.classList.remove("valido");
    txt.classList.remove("novalido");
    if (txt.value.trim().length < min ||
        txt.value.trim().length > max) {
        txt.setCustomValidity("Campo no válido");
        txt.classList.add("novalido");
        return false;
    } else {
        txt.classList.add("valido");
        return true;
    }
}