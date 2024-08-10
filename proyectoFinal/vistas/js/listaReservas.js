document.addEventListener("DOMContentLoaded", () => {


    document.getElementById("mdlEliminar").addEventListener('shown.bs.modal', (e) => {
        let id=e.relatedTarget.value;
        let nombre=e.relatedTarget.getAttribute("nombre");
        document.getElementById("btnEliminar").value=id;
        document.getElementById("UsuarioEliminar").innerText=nombre;
        
    });
});
