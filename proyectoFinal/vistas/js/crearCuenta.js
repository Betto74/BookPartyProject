document.addEventListener('DOMContentLoaded', () => {
    let txtNombre = document.getElementById("txtNombre");
    txtNombre.classList.remove("valid");
    txtNombre.classList.remove("invalid");

    document.getElementById('txtNombre').addEventListener('keyup', () => {
        debugger;
        let txtNombre = document.getElementById("txtNombre");
        txtNombre.setCustomValidity("");
        if (txtNombre.value.length < 2 || txtNombre.value.length > 50) {
            txtNombre.setCustomValidity("El nombre es obligatorio y debe tener entre 2 y 50 caracteres.");
            
            txtNombre.classList.remove("valido");
            txtNombre.classList.add("novalido");
        }
        else{
            txtNombre.classList.add("valido");
            txtNombre.classList.remove("novalido");
        }
    });

    document.getElementById('txtCorreo').addEventListener('keyup', (e) => {
        if (e.code == "Tab") return;
        var regCorreo = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/g;
        let txtCorreo = document.getElementById("txtCorreo");
        txtCorreo.setCustomValidity("");
        debugger;
        if (!txtCorreo.value.match(regCorreo)) {
            txtCorreo.setCustomValidity("El email es obligatorio y debe tener un formato válido.");
            
            txtCorreo.classList.remove("valido");
            txtCorreo.classList.add("novalido");
        }
        else{
            txtCorreo.classList.add("valido");
            txtCorreo.classList.remove("novalido");
        }
    });

    document.getElementById('txtContrasenia').addEventListener('keyup', () => {
        let txtContrasenia = document.getElementById("txtContrasenia");
        txtContrasenia.setCustomValidity("");
        if (txtContrasenia.value.length > 50 || txtContrasenia.value.length < 8) {
            txtContrasenia.setCustomValidity("La contraseña es obligatoria y debe tener entre 8 y 50 caracteres.");
            
            txtContrasenia.classList.remove("valido");
            txtContrasenia.classList.add("novalido");
        }
        else{
            txtContrasenia.classList.add("valido");
            txtContrasenia.classList.remove("novalido");
        }
    });

    document.getElementById('txtConfirmarContrasenia').addEventListener('keyup', () => {
        let txtConfirmarContrasenia = document.getElementById("txtConfirmarContrasenia");
        let txtContrasenia = document.getElementById("txtContrasenia");
        txtConfirmarContrasenia.setCustomValidity("");
        if (txtConfirmarContrasenia.value !== txtContrasenia.value) {
            txtConfirmarContrasenia.setCustomValidity("Las contraseñas deben coincidir.");
            txtConfirmarContrasenia.classList.remove("valido");
            txtConfirmarContrasenia.classList.add("novalido");
        }
        else{
            txtConfirmarContrasenia.classList.add("valido");
            txtConfirmarContrasenia.classList.remove("novalido");
            
        }
    });

    document.getElementById('txtTelefono').addEventListener('keyup', () => {
        let txtTelefono = document.getElementById("txtTelefono");
        txtTelefono.setCustomValidity("");
        if (txtTelefono.value.length !== 10) {
            txtTelefono.setCustomValidity("El teléfono debe tener 10 dígitos.");
            
            txtTelefono.classList.remove("valido");
            txtTelefono.classList.add("novalido");
        }
        else{
            txtTelefono.classList.add("valido");
            txtTelefono.classList.remove("novalido");
        }
    });

    document.getElementById("btnAceptar").addEventListener("click", e => {
        e.target.form.classList.add("validado");
        
        let controles = e.target.form.querySelectorAll("input,select");
        controles.forEach(control => {
            control.setCustomValidity("");
        });

        checarTodo();

        if (!e.target.form.checkValidity()) {
            e.preventDefault();
        }
    });
});



function checarTodo(){
        let txtNombre = document.getElementById("txtNombre");
        txtNombre.setCustomValidity("");
        if (txtNombre.value.length < 2 || txtNombre.value.length > 50) {
            txtNombre.setCustomValidity("El nombre es obligatorio y debe tener entre 2 y 50 caracteres.");
            return false;
        }

        var regCorreo = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/g;
        let txtCorreo = document.getElementById("txtCorreo");
        txtCorreo.setCustomValidity("");
        if (!txtCorreo.value.match(regCorreo)) {
            txtCorreo.setCustomValidity("El email es obligatorio y debe tener un formato válido.");
            return false;
        }

        let txtContrasenia = document.getElementById("txtContrasenia");
        txtContrasenia.setCustomValidity("");
        if (txtContrasenia.value.length > 50 || txtContrasenia.value.length < 8) {
            txtContrasenia.setCustomValidity("La contraseña es obligatoria y debe tener entre 8 y 50 caracteres.");
            return false;
        }

        let txtConfirmarContrasenia = document.getElementById("txtConfirmarContrasenia");
        txtConfirmarContrasenia.setCustomValidity("");
        if (txtConfirmarContrasenia.value !== txtContrasenia.value) {
            txtConfirmarContrasenia.setCustomValidity("Las contraseñas deben coincidir.");
            return false;
        }

        let txtTelefono = document.getElementById("txtTelefono");
        txtTelefono.setCustomValidity("");
        if (txtTelefono.value.length !== 10) {
            txtTelefono.setCustomValidity("El teléfono debe tener 10 dígitos.");
            return false;
        }


        return true;

}
