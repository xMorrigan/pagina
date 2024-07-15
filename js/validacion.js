function validar_registro(event) {
    let nombre = document.getElementById("nombre").value;
    let apellidos = document.getElementById("apellidos").value;
    let email = document.getElementById("email").value;
    let pwd = document.getElementById("password").value;
    let pwd1 = document.getElementById("passwordC").value;
    let camposVacios = false; // Variable para rastrear campos vacíos
    var re = /\S+@\S+\.\S+/;
    if (nombre.trim() === "" || apellidos.trim() === "" || email.trim() === "" || pwd.trim() === "" || pwd1.trim() === "") {
        camposVacios = true;
    }
    if (camposVacios) {
        alert("Debe rellenar todos los campos.");
        event.preventDefault();
    } else if (pwd.length < 8) {
        alert("La contraseña debe tener al menos 8 caracteres.");
        event.preventDefault();
    }
    else if(!re.test(email)) {
        alert('El correo electrónico ingresado no es válido.');
        event.preventDefault();
    }
    else if (pwd !== pwd1) {
        alert("Las contraseñas no coinciden.");
        event.preventDefault();
    }
    else {
        document.registro.submit();
    }
}

function validar_login(event){
    let correo  = document.getElementById("email").value;
    let pwd  = document.getElementById("password").value;
    if(correo == "" || pwd == "")
    {
        alert("Debe rellenar los campos faltantes");
        event.preventDefault();
    } 
    var re = /\S+@\S+\.\S+/;
    if(!re.test(correo)) {
        alert('El correo electrónico ingresado no es válido.');
        return false;
    }
    else{
        document.form.submit();
    }
}