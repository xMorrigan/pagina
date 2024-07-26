<?php session_start();
$varsession = $_SESSION['id'];
if($varsession == null || $varsession== ''){
    echo "<script>alert('Debes iniciar sesión para añadir al carrito');</script>";
    echo "<script>window.location ='login.php';</script>";
    exit();
}
include 'conexion.php';
$productosC = "SELECT * FROM personas";
$productos = mysqli_query($conexion, $productosC);
$productos1 = mysqli_fetch_array($productos);
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$consulta_busqueda = "SELECT * FROM personas WHERE nombre LIKE '%$busqueda%'";
$resultado_busqueda = mysqli_query($conexion, $consulta_busqueda);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['id'])) { 
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $aumero = $_POST['aumero'];
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];
    $area = $_POST['area'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordC = $_POST['passwordC'];
    $estado_region = $_POST['estado_region'];
    $rol = $_POST['rol'];

    if ($password !== $passwordC) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    } else {
        $insertar = "INSERT INTO personas (nombre, apellidos, aumero, direccion, codigo_postal, area, email, password, estado_region, rol) VALUES ('$nombre', '$apellidos', '$aumero', '$direccion', '$codigo_postal', '$area', '$email', '$password', '$estado_region', '$rol')";
        if (mysqli_query($conexion, $insertar)) {
            echo "<script>alert('Se ha registrado exitosamente'); window.location='cat_usuarios.php';</script>";
        } 
    }
}

if(isset($_REQUEST['eliminar'])){
    $eliminar = $_REQUEST['eliminar'];
    mysqli_query($conexion,"delete from personas where id=$eliminar");
    echo "<script> alert('Usuario eliminado'); </script>";
    echo "<script> window.location='cat_usuarios.php' </script>";
}

if(isset($_REQUEST['editar'])){
    $editar = $_REQUEST['editar'];
    $registro = mysqli_query($conexion,"select * from personas where id= $editar");
    $reg = mysqli_fetch_array($registro);}

if(isset($_REQUEST['id'])){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $aumero = $_POST['aumero'];
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];
    $area = $_POST['area'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordC = $_POST['passwordC'];
    $estado_region = $_POST['estado_region'];
    $rol = $_POST['rol'];
    
    if ($password !== $passwordC) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    } else {
        $insertar = "UPDATE personas SET nombre='$nombre',apellidos='$apellidos', aumero='$aumero', direccion= '$direccion', codigo_postal='$codigo_postal', area= '$area', email= '$email', password='$password', estado_region='$estado_region', rol='$rol' where id = '$id'";
        if (mysqli_query($conexion, $insertar)) {
            echo "<script>alert('Se ha actualizado exitosamente'); window.location='cat_usuarios.php';</script>";
        } 
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Usuarios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <script language="javascript">
        function preguntar(){
            var elimina = confirm("¿Desea eliminar el registro?");
            if(elimina){
                return true;
            }
            else{
                return false;
            }
        }
    </script>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">Carretera, Villahermosa-Teapa Km. 14.6, 86288 Parrilla II, Tab. México</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">mine810.40@gmail.com</a></small>
                </div>
                <div class="top-link pe-2">
                    <a href="#" class="text-white"><small class="text-white mx-2">Politica y Privacidad</small>

                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">La Ocasión </h1></a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link active">Inicio</a>
                        <a href="shop.php" class="nav-item nav-link">Tienda</a>
                        <a href="shop-detail.php" class="nav-item nav-link">Productos</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Otras Paginas</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="cart.php" class="dropdown-item">Carrito</a>
                                <a href="chackout.php" class="dropdown-item">Revisar Compra</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contactos</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                        </a>
                        <a href="Profile.php" class="my-auto">
                            <i class="fas fa-user fa-2x"></i>
                        </a>
                    </div>
                </div>
            </nav>
        </div>  
    </div>
    <!-- Navbar End -->


    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Usuarios</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="dashboard.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="cat_usuarios.php">Usuarios</a></li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Cart Page Start -->
        
        <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3 mx-5">
                        <h4 class="text-right">Usuarios</h4>
                    </div>
                    
                    <div class="row mt-2">
                    <form  action="" method="post" id="registro" onsubmit="validar_registro(event)">
                        <div class="col-md-6"><label class="labels">Nombre</label>
                        <input id="nombre"  name="nombre" type="text" class="form-control" placeholder="Nombre"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['nombre']."' "; }?>></div>
                        <div class="col-md-6"><label class="labels">Apellidos</label><input id="apellidos" name="apellidos" type="text" class="form-control" <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['apellidos']."' "; }?> placeholder="Apellidos"></div>
                      
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 mt-3">
                            <label for="rol" class="labels">Rol</label>
                            <select id="rol" name="rol" class="form-control">
                                <option value="<?php if(isset($_REQUEST['editar'])){ echo $reg['rol']; }?>"><?php if(isset($_REQUEST['editar'])){ echo $reg['rol']; }?></option>
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3"><label class="labels">Numero</label><input id="aumero" name="aumero" type="text" class="form-control" placeholder="Ingresa tu Número telefónico"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['aumero']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Dirección</label><input id="direccion" name="direccion" type="text" class="form-control" placeholder="Ingresa tu dirección"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['direccion']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Código Postal</label><input id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Ingresa tu código postal"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['codigo_postal']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Area</label><input id="area" name="area" type="text" class="form-control" placeholder="Ingresa tu colonia"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['area']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Correo</label><input id="email" name="email" type="text" class="form-control" placeholder="Ingresa tu Correo Electronico"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['email']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Contraseña</label><input id="password" name="password" type="password" class="form-control" placeholder="Ingresa una contraseña"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['password']."' "; }?>></div>
                        <div class="col-md-12 mt-3"><label class="labels">Confirmación de contraseñas</label><input id="passwordC" name="passwordC" type="password" class="form-control" placeholder="Ingresa una contraseña"></div>
                        <div class="col-md-12 mt-3"><label class="labels">Estado/Región</label><input id="estado_region" name="estado_region" type="text" class="form-control"  <?php if(isset($_REQUEST['editar'])){ echo "value='".$reg['estado_region']."' "; }?> placeholder="Estado"></div>    
                    </div>
                    <div class="mt-5 px-10 text-center">
                    <input type="submit" class="btn btn-primary profile-button" <?php if(isset($_REQUEST['editar'])){ echo "value='Guardar'";}else{"value='Insertar'";}?> id="boton" >    
                    <?php 
                    if(isset($_REQUEST['editar'])) { 
                        echo "<input type='hidden' name='id' value='".$reg['id']."'>"; }
                    ?>
                    </form>
                   </div>
                </div>
                
            </div>

            <div class="input-group">
                <form method="GET" action="cat_usuarios.php">
                    <div class="row w-100 mb-10">
                        <input name="busqueda" type="text" class="col form-control rounded" placeholder="Buscar" aria-label="Search" aria-describedby="search-addon" />
                        <button type="submit" class="col btn btn-outline-primary" data-mdb-ripple-init><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <?php
            if (mysqli_num_rows($resultado_busqueda) > 0) {
            ?>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Clave</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Eliminar</th>
                    <th scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($resultado = mysqli_fetch_array($resultado_busqueda)) { ?>
                    <tr>
                    <th scope="row"><?php echo $resultado['id']; ?></th>
                    <td><?php echo $resultado['nombre']; ?></td>
                    <td><?php echo $resultado['apellidos']; ?></td>
                    <td><?php echo $resultado['email']; ?></td>
                    <td><?php echo $resultado['rol']; ?></td>
                    <td><a onclick="return preguntar()" href="cat_usuarios.php?eliminar=<?php echo $resultado['id']; ?>">Eliminar</a></td>
                    <td><a href="cat_usuarios.php?editar=<?php echo $resultado['id']; ?>">Editar</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                <?php } else { ?>
                    <p>No se encontraron resultados para la búsqueda.</p>
                <?php } ?>
                </div>
            </div>
        </div>

        
    </div>
    </div>
    </div>

        <!-- Cart Page End -->


             <!-- Footer Start -->
             <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
                <div class="container py-5">
                    <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                        <div class="row g-4">
                            <div class="col-lg-3">
                                <a href="#">
                                    <h1 class="text-primary mb-0">LA OCASIÓN WESTERN & CHARRO</h1>
                                    <p class="text-secondary mb-0">"Con el porte de un charro y la esencia del rancho, vivimos la tradición mexicana."</p>
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <div class="position-relative mx-auto">
                                    <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Tu correo">
                                    <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Registrate!</button>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex justify-content-end pt-3">
                                    <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                    <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5">
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-item">
                                <h4 class="text-light mb-3">Calidad Garantizada!</h4>
                                <p class="mb-4">Desde 1972, en nuestra tienda celebramos la grandeza de los charros mexicanos y la vida en el rancho,
                                    ofreciendo productos auténticos que honran la tradición y el orgullo de nuestra herencia.</p>
                                <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Leer Más</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex flex-column text-start footer-item">
                                <h4 class="text-light mb-3">Detalles de tienda</h4>
                                <a class="btn-link" href="">Contactanos</a>
                                <a class="btn-link" href="">Politica y Privacidad</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex flex-column text-start footer-item">
                                <h4 class="text-light mb-3">Crear una cuenta</h4>
                                <a class="btn-link" href="">Mi perfil</a>
                                <a class="btn-link" href="">Detalles de compra</a>
                                <a class="btn-link" href="">Carrito</a>
                                <a class="btn-link" href="">Historial de compras</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-item">
                                <h4 class="text-light mb-3">Contacto</h4>
                                <p>Dirección: Villahermosa-Teapa 86288 Parrilla II, Tab. México</p>
                                <p>Correo: mine810.40@gmail.com</p>
                                <p>Telefono: 9934475060</p>
                                <p>Payment Accepted</p>
                                <img src="img/payment.png" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
    
            <!-- Copyright Start -->
            <div class="container-fluid copyright bg-dark py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>COMERCIALIZADORA LA OCASIÓN S.A DE C.V</a>, Todos los Derechos Reservados.</span>
                        </div>
                        <div class="col-md-6 my-auto text-center text-md-end text-white">
                            <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                            <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                            <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Manejar incremento y decremento de cantidad
                function updateQuantity(change, carritoId) {
                    var cantidadInput = document.getElementById('cantidad-' + carritoId);
                    var currentQuantity = parseInt(cantidadInput.value);

                    if (!isNaN(currentQuantity)) {
                        var newQuantity = currentQuantity + change;
                        if (newQuantity > 0) { // Evitar cantidades negativas
                            cantidadInput.value = newQuantity;
                        }
                    }
                }

                // Obtener todos los botones de incremento y decremento
                document.querySelectorAll('button[id^="buttonRes-"], button[id^="buttonSum-"]').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault(); // Evitar el comportamiento predeterminado
                        var carritoId = this.id.split('-')[1];
                        var change = this.id.startsWith('buttonRes') ? -1 : 1;
                        updateQuantity(change, carritoId);
                    });
                });
            });
            </script>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>