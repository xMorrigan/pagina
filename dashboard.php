<?php session_start();
$varsession = $_SESSION['id'];
if($varsession == null || $varsession== ''){
    echo "<script>alert('Debes iniciar sesión para añadir al carrito');</script>";
    echo "<script>window.location ='login.php';</script>";
    exit();
}
include 'conexion.php';
$tp = "SELECT COUNT(*) as total FROM productos";
$result = mysqli_query($conexion, $tp);
$row = mysqli_fetch_assoc($result);
$total_productos = $row['total'];

$tu = "SELECT COUNT(*) as total FROM personas";
$resultU = mysqli_query($conexion, $tu);
$rowU = mysqli_fetch_assoc($resultU);
$total_usuarios = $rowU['total'];

$productos_sm = "SELECT * FROM `productos` WHERE existencia < stock_minimo";
$resultadoP = mysqli_query($conexion, $productos_sm);
$resultadoP1 = mysqli_fetch_array($resultadoP);

$tpsm = "SELECT COUNT(*) as total FROM productos WHERE existencia < stock_minimo";
$resultSM = mysqli_query($conexion, $tpsm);
$rowSM = mysqli_fetch_assoc($resultSM);
$total_PSM = $rowSM['total'];

$productosC = "SELECT * FROM productos";
$productos = mysqli_query($conexion, $productosC);
$productos1 = mysqli_fetch_array($productos);
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$consulta_busqueda = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%'";
$resultado_busqueda = mysqli_query($conexion, $consulta_busqueda);
if($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['id'])){
    $nombre = $_REQUEST['nombre'];
    $descripcion = $_REQUEST['descripcion'];
    $precio = $_REQUEST['precio'];
    $categoria = $_REQUEST['categoria'];
    $stock_minimo = $_REQUEST['stock_minimo'];
    $stock_maximo = $_REQUEST['stock_maximo'];
    $existencia = $_REQUEST['existencia'];

    $subio = false;
    $directorio = 'img';
    $foto = $directorio."/".$_FILES['img']['name'];

    if(is_uploaded_file($_FILES['img']['tmp_name'])){
        move_uploaded_file($_FILES['img']['tmp_name'], $foto);
        $subio=true;
    // cambio aqui se cerro la llave, revisa las llaves aqui
    }
    if($subio){
        $insertar = "INSERT INTO productos(nombre,descripcion,precio,categoria,stock_minimo,stock_maximo,existencia,img) values('$nombre','$descripcion','$precio','$categoria','$stock_minimo','$stock_maximo','$existencia','$foto')";
        mysqli_query($conexion,$insertar);
        echo "<script> alert('Producto registrado'); </script>";
        echo "<script> window.location='inventario.php'; </script>";
    }
    else{
        $foto = 'img/default.jpg'; // Ruta de la imagen por defecto
        $insertar = "INSERT INTO productos(nombre,descripcion,precio,categoria,stock_minimo,stock_maximo,existencia,img) values('$nombre','$descripcion','$precio','$categoria','$stock_minimo','$stock_maximo','$existencia','$foto')";
        mysqli_query($conexion, $insertar);
        echo "<script> alert('Producto registrado con imagen por defecto.'); </script>";
        echo "<script> window.location='inventario.php'; </script>";
    }
}

if(isset($_REQUEST['eliminar'])){
    $eliminar = $_REQUEST['eliminar'];
    mysqli_query($conexion,"delete from productos where id=$eliminar");
    echo "<script> alert('Producto borrado'); </script>";
    echo "<script> window.location='inventario.php' </script>";
}

if(isset($_REQUEST['editar'])){
    $editar = $_REQUEST['editar'];
    $registro = mysqli_query($conexion,"select * from productos where id= $editar");
    $reg = mysqli_fetch_array($registro);}

if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $nombre = $_REQUEST['nombre'];
    $descripcion = $_REQUEST['descripcion'];
    $precio = $_REQUEST['precio'];
    $categoria = $_REQUEST['categoria'];
    $stock_minimo = $_REQUEST['stock_minimo'];
    $stock_maximo = $_REQUEST['stock_maximo'];
    $existencia = $_REQUEST['existencia'];

    $subio = false;
    $directorio = 'img';
    $foto = $directorio."/".$_FILES['img']['name'];


    if(is_uploaded_file($_FILES['img']['tmp_name'])){
        move_uploaded_file($_FILES['img']['tmp_name'], $foto);
        $subio=true;
        // cambio  aqui lo mismo de las llaves
    }
    if($subio){
        mysqli_query($conexion,"update productos set nombre='$nombre',descripcion='$descripcion', precio ='$precio', categoria= '$categoria', stock_minimo= '$stock_minimo', stock_maximo= '$stock_maximo', existencia= '$existencia', img='$foto' where id = '$id'");
        echo "<script> alert('Producto actualizado'); </script>";
        echo "<script> window.location='inventario.php'; </script>";
    }
    else{
        // cambio le agregue las lineas de abajo es en caso de no modificar la imagen
        $imagen_existente = $_REQUEST['archivo_mod'];
        mysqli_query($conexion,"update productos set nombre='$nombre',descripcion='$descripcion', precio ='$precio', categoria= '$categoria', stock_minimo= '$stock_minimo', stock_maximo= '$stock_maximo', existencia= '$existencia' where id = '$id'");
        echo "<script> alert('Producto actualizado'); </script>";
        echo "<script> window.location='inventario.php'; </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
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

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Dashboard</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            </ol>
        </div>
        <!-- Single Page Header End -->
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand fs-1 text-primary mb-0" href="index.php">LA OCASIÓN </a><span class="text-primary-emphasis">Dashboard</s>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon text-success"><i class="fa fa-solid fa-bars py-1"></i></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">La ocasión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Perfil</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administrador
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dashboard.php">Inicio del dashboard</a></li>
                        <li><a class="dropdown-item" href="cat_usuarios.php">Usuarios</a></li>
                        <li><a class="dropdown-item" href="inventario.php">Productos</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                    </ul>
                </div>
                </div>
            </div>
            </nav>

        <!-- Cart Page Start -->
        <div class="container-fluid mt-5">
       
            <div class="mt-20 row">
                <div class="card text-bg-primary col mx-4">
                    <div class="card-body row">
                        <h5 class="card-title col"><i class="text-center fa fa-shopping-bag fa-5x py-1 text-primary"></i></h5>
                        <p class="card-text col fs-3 text-primary"><span class="fs-1"><?php echo $total_productos; ?></span><br> Productos</p>
                    </div>
                </div>
                <div class="card text-bg-primary col mx-4">
                    <div class="card-body row">
                        <h5 class="card-title col"><i class="text-center fa fa-solid fa-exclamation fa-5x py-2 text-danger"></i></h5>
                        <p class="card-text col fs-5 text-danger"><span class="fs-1"><?php echo $total_PSM; ?></span><br> Productos insuficientes</p>
                    </div>
                </div>
                <div class="card text-bg-primary col mx-4">
                    <div class="card-body row">
                        <h5 class="card-title col"><i class="text-center fa fa-solid fa-user fa-5x py-1 text-info-subtle"></i></h5>
                        <p class="card-text col fs-3 text-info-subtle"><span class="fs-1"><?php echo $total_usuarios; ?></span><br> Usuarios</p>
                    </div>
                </div>
                <div class="card text-bg-primary col mx-4">
                    <div class="card-body row">
                        <h5 class="card-title col"><i class="text-center fa fa-shopping-bag fa-5x py-1 text-success"></i></h5>
                        <p class="card-text col fs-3 text-success"><span class="fs-1"><?php echo $total_productos; ?></span><br> Ventas</p>
                    </div>
                </div>
            </div>


            <div class="mt-5 row">
                <div class="card text-bg-primary col mx-4">
                <h1 class="text-center mt-2">Productos por agotarse</h1>
                    <div class="card-body row">
                    
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col"></th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Existencia</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($resultadoP) > 0) {  ?>
                    <?php do { ?>
                    <tr>
                    <th scope="row">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo $resultadoP1['img']; ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                    </div>
                    </th>
                    <td><?php echo $resultadoP1['nombre']; ?></td>
                    <?php
                    $existencia = isset($resultadoP1['existencia']) ? $resultadoP1['existencia'] : 0;
                    $stock_minimo = isset($resultadoP1['stock_minimo']) ? $resultadoP1['stock_minimo'] : 0;
                    $stock_maximo = isset($resultadoP1['stock_maximo']) ? $resultadoP1['stock_maximo'] : 0;

                    $existencia_class = '';
                    if ($existencia < $stock_minimo) {
                        $existencia_class = 'bg-danger text-white';
                    } elseif ($existencia > $stock_maximo) {
                        $existencia_class = 'bg-success text-white';
                    }
                    ?>
                    <td class="<?php echo $existencia_class; ?>"><?php echo $resultadoP1['existencia']; ?></td>
                    </tr>
                </tbody>
                </table>
                <?php } while($resultadosP1 = mysqli_fetch_array($resultadoP));?>
                <?php } else { ?>
                    <p>Todos los productos estan abastecidos</p>
                <?php } ?>
                    </div>
                </div>
                <div class="card text-bg-primary col mx-4">
                    <div class="card-body row">
                        <h5 class="card-title col"><i class="text-center fa fa-solid fa-exclamation fa-5x py-2 text-danger"></i></h5>
                        <p class="card-text col fs-5 text-danger"><span class="fs-1"><?php echo $total_PSM; ?></span><br> Productos insuficientes</p>
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