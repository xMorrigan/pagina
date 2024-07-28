<?php session_start();
$varsession = $_SESSION['id'];
if($varsession == null || $varsession== ''){
    echo "<script>alert('Debes iniciar sesión para añadir al carrito');</script>";
    echo "<script>window.location ='login.php';</script>";
    exit();
}
include 'conexion.php';

$total_ganado_ventas = "
    SELECT 
        SUM(dv.cantidad * dv.precio_unitario) AS total_venta
    FROM 
        ventas v
    JOIN 
        detalles_venta dv ON v.id = dv.id_venta;
";
$resultTotal = $conexion->query($total_ganado_ventas);


$productos_sm = "SELECT * FROM `productos` WHERE existencia < stock_minimo";
$resultadoP = mysqli_query($conexion, $productos_sm);
$resultadoP1 = mysqli_fetch_array($resultadoP);

$topProductos = "SELECT 
    p.id AS producto_id,
    p.nombre AS producto_nombre,
    SUM(dv.cantidad) AS cantidad_total_vendida,
    p.precio,
    p.img,
    SUM(dv.cantidad * dv.precio_unitario) AS total_ganado
FROM 
    detalles_venta dv
JOIN 
    productos p ON dv.id_producto = p.id
GROUP BY 
    p.id, p.nombre, p.precio
ORDER BY 
    cantidad_total_vendida DESC LIMIT 4";
$resultadoTP = mysqli_query($conexion, $topProductos);
$resultadoTP1 = mysqli_fetch_array($resultadoTP);

$ventasQuery = "
    SELECT 
        v.id AS numero_venta,
        v.fecha_venta,
        SUM(dv.cantidad * dv.precio_unitario) AS total_ganado,
        GROUP_CONCAT(CONCAT(p.nombre, ' (', dv.cantidad, ')') SEPARATOR ', ') AS productos_vendidos
    FROM 
        ventas v
    JOIN 
        detalles_venta dv ON v.id = dv.id_venta
    JOIN 
        productos p ON dv.id_producto = p.id
    GROUP BY 
        v.id, v.fecha_venta;
";
$resultadoV = mysqli_query($conexion, $ventasQuery);
$resultadoV1 = mysqli_fetch_array($resultadoV);

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$consulta_busqueda = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%'";
$resultado_busqueda = mysqli_query($conexion, $consulta_busqueda);


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
        <div class="text-center mt-20 row">
            <h1 class="mt-2 text-info">Productos más vendidos </h1>
            <?php if (mysqli_num_rows($resultadoTP) > 0) {  ?>
                <?php do { ?>
                <div class="card mx-4" style="width: 18rem;">
                <img class="card-img-top" src="<?php echo $resultadoTP1['img']; ?>" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title text-center"><?php echo $resultadoTP1['producto_nombre']; ?></h5>
                </div>
                </div>
                <?php } while($resultadoTP1 = mysqli_fetch_array($resultadoTP));?>
                        <?php } else { ?>
                            <p>Aún no hay productos vendidos</p>
                        <?php } ?>
               
            </div>


            <div class="mt-5 row">

                <div class="card text-bg-primary col mx-4">
                    
                <h1 class="text-center mt-2 text-dark">Ventas <br><span class="text-warning"><?php 
                    if ($resultTotal) {
                        $row = $resultTotal->fetch_assoc();
                        echo "Ganancias: $" . $row['total_venta'];
                    } else {
                        echo "Error al calcular el total ganado: " . $conexion->error;
                    }?></span> </h1>
                    <div class="card-body row">
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Fecha de venta</th>
                                <th scope="col">Total de la venta</th>
                                <th scope="col">Productos vendidos</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($resultadoV) > 0) {  ?>
                            <?php do { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($resultadoV1['fecha_venta']); ?></td>
                                <td><?php echo htmlspecialchars($resultadoV1['total_ganado']); ?></td>
                                <td><?php echo htmlspecialchars($resultadoV1['productos_vendidos']); ?></td>
                            </tr>
                            <?php } while($resultadoV1 = mysqli_fetch_array($resultadoV));?>
                        <?php } else { ?>
                            <p>Aún no hay ventas</p>
                        <?php } ?>
                        </tbody>
                    </table>

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