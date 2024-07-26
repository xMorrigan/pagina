<?php
include 'conexion.php';
$consulta_personas = 'select * from personas';
$personas = mysqli_query($conexion,$consulta_personas);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) { 
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordC = $_POST['passwordC'];

    if ($password !== $passwordC) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    } else {
        $insertar = "INSERT INTO personas (nombre, apellidos, aumero, direccion, codigo_postal, area, email, password, estado_region, rol) VALUES ('$nombre', '$apellidos', '5', 'S/D', 'S/D', 'S/D', '$email', '$password', 'S/D', 'user')";
        if (mysqli_query($conexion, $insertar)) {
            echo "<script>alert('Se ha registrado exitosamente'); window.location='login.php';</script>";
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Registrate</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="/LA OCASION/img/LOGO.png"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="csss/util.css">
	<link rel="stylesheet" type="text/css" href="csss/main.css">
	<script src="js/validacion.js"></script>
</head>
<body>
	
	<div class="limiter">
	  <div class="container-login100">
		<div class="wrap-login100">
		  <form class="login100-form validate-form" action="" method="post" id="registro" onsubmit="validar_registro(event)">
			<span class="login100-form-title p-b-26 text-center">
			  Registrate
			  <img src="img/LOGO.png" class="img-fluid rounded" class="align-items-center"  style="width: 50px; height: 50px;"alt="">

			<div class="wrap-input100 validate-input" data-validate="Debe iniciar con una letra mayúscula">
				<input class="input100" type="text" name="nombre" id="nombre">
				<span class="focus-input100" data-placeholder="Nombre"></span>
			  </div>

			  <div class="wrap-input100 validate-input" data-validate="Debe iniciar con una letra mayúscula">
				<input class="input100" type="text" name="apellidos" id="apellidos ">
				<span class="focus-input100" data-placeholder="Apellidos"></span>
			  </div>

			<div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
			<input class="input100" type="text" name="email" id="email">
			<span class="focus-input100" data-placeholder="Correo Electronico"></span>
			</div>

			<div class="wrap-input100 validate-input" data-validate="Enter password">
			  <span class="btn-show-pass">
			  </span>

			  <input class="input100" type="password" name="password" id="password">
			  <span class="focus-input100" data-placeholder="Contraseña"></span>
			</div>
			
			<div class="wrap-input100 validate-input" data-validate="Confirm Password">
				<span class="btn-show-pass">
				</span>
  
				<input class="input100" type="password" name="passwordC" id="passwordC">
				<span class="focus-input100" data-placeholder="Confirmar Contraseña"></span>
			  </div>
			  
	
  
			<div class="container-login100-form-btn">
			  <div class="wrap-login100-form-btn">
				<a href="index.php" class="login100-form-bgbtn">
				  <div class="login100-form-bgbtn"></div>
				</a>
				<button class="login100-form-btn"  type="submit" onclick="validar_registro(event)">Registrar</button>

			  </div>
			</div>

  
			<div class="text-center p-t-115">
			  <span class="txt1">
				Ya tienes una cuenta?
			  </span>
  
			  <a class="txt2" href="login.php">>
				Inicia sesion
			  </a>
			</div>
		  </form>
		</div>
	  </div>
	</div>
  </body>
  

	<div id="dropDownSelect1"></div>
	
</body>
</html>