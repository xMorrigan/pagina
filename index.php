<?php session_start();
$varsession = $_SESSION['id'];

include 'conexion.php';

$productosC = "SELECT * FROM productos ORDER BY id DESC LIMIT 9";
$productos = mysqli_query($conexion, $productosC);
$productos1 = mysqli_fetch_array($productos);
$botinesC = "SELECT * FROM productos WHERE categoria = 'botines'";
$botines = mysqli_query($conexion, $botinesC);
$botines1 = mysqli_fetch_array($botines);
$sombrerosC = "SELECT * FROM productos WHERE categoria = 'sombreros'";
$sombreros = mysqli_query($conexion, $sombrerosC);
$sombreros1 = mysqli_fetch_array($sombreros);
$trajesC = "SELECT * FROM productos WHERE categoria = 'trajes'";
$trajes = mysqli_query($conexion, $trajesC);
$trajes1 = mysqli_fetch_array($trajes);
$camisasC = "SELECT * FROM productos WHERE categoria = 'camisas'";
$camisas = mysqli_query($conexion, $camisasC);
$camisas1 = mysqli_fetch_array($camisas);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
    $id_usuario = $_SESSION['id'];
    $id_producto = $_POST['id_producto'];
    $cantidad = 1;
    $talla = isset($_POST['talla']) ? $_POST['talla'] : NULL; // Asegúrate de manejar la talla si es necesaria

       // Verificar el stock disponible
       $stockQuery = "SELECT existencia FROM productos WHERE id = '$id_producto'";
       $stockResult = mysqli_query($conexion, $stockQuery);
       $stockData = mysqli_fetch_array($stockResult);
   
       if ($stockData['existencia'] > 0) {
           // Verificar si el producto ya está en el carrito
           $checkCarritoQuery = "SELECT * FROM carrito_usuarios WHERE id_sesion='$id_usuario' AND id_producto='$id_producto' AND talla='$talla'";
           $result = mysqli_query($conexion, $checkCarritoQuery);
   
           if (mysqli_num_rows($result) > 0) {
               // Producto ya está en el carrito, actualizar la cantidad
               $updateQuery = "UPDATE carrito_usuarios SET cantidad = cantidad + 1 WHERE id_sesion='$id_usuario' AND id_producto='$id_producto' AND talla='$talla'";
               if (mysqli_query($conexion, $updateQuery)) {
                $_SESSION['toast_message'] = 'Cantidad actualizada en el carrito';
               } else {
                   echo "Error al actualizar la cantidad: " . mysqli_error($conexion);
               }
           } else {
               // Producto no está en el carrito, insertar nuevo registro con cantidad 1
               $insertQuery = "INSERT INTO carrito_usuarios (id_sesion, id_producto, talla, cantidad) VALUES ('$id_usuario', '$id_producto', '$talla', 1)";
               if (mysqli_query($conexion, $insertQuery)) {
                $_SESSION['toast_message'] = 'Producto añadido al carrito';
               } else {
                   echo "Error al añadir el producto: " . mysqli_error($conexion);
               }
           }

           
           $nuevoStock = $stockData['existencia'] - 1;
           $updateStockQuery = "UPDATE productos SET existencia = '$nuevoStock' WHERE id = '$id_producto'";
           mysqli_query($conexion, $updateStockQuery);
   
       } else {
            $_SESSION['toast_message'] = 'Stock insuficiente';
       }
       header("Location: " . $_SERVER['PHP_SELF']);
       exit();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>La Ocasión Botas y Sombreros</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

              <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
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
             ->
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
        


           <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-12 col-lg-7">
                        <h4 class="mb-3 text-secondary">Productos Totalmente Mexicanos</h4>
                        <h1 class="mb-5 display-3 text-primary">Western & Charro </h1>
                        <div class="position-relative mx-auto">
                            <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="number" placeholder="Buscalo mas facil">
                            <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-5">
                        <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active rounded">
                                    <img src="img/Botin_1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                    <a href="#" class="btn px-4 py-2 text-white rounded">Botas y Botines</a>
                                </div>
                                <div class="carousel-item rounded">
                                    <img src="img/Sombrero_1.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                                    <a href="#" class="btn px-4 py-2 text-white rounded">Sombreros</a>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="container py-5">
                <div class="tab-class text-center">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start">
                            <h1>Nuestros Productos</h1>
                        </div>
                        <div class="col-lg-8 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">Todos los Productos</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                        <span class="text-dark" style="width: 130px;">Botines</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                        <span class="text-dark" style="width: 130px;">Sombreros</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-5">
                                        <span class="text-dark" style="width: 130px;">Trajes regionales</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <?php if ($productos) {while ($fila = mysqli_fetch_assoc($productos)) {?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                <a href="shop-detail.php?id=<?php echo $fila['id']; ?>">
                                    <div class="rounded position-relative fruite-item">
                                        <div class="fruite-img">
                                            <img src="<?php echo $fila['img']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                        </div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4><?php echo $fila['nombre']; ?></h4>
                                            <p><?php echo $fila['descripcion']; ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0">$<?php echo $fila['precio']; ?></p>
                                            <form class="add-to-cart-form" method="POST" action="">
                                            <input type="hidden" name="id_producto" value="<?php echo $fila['id']; ?>">
                                            <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                            </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            <?php } } else {echo "No se encontraron registros.";} ?>
                        </div>
                    </div>
                       
                        <div id="tab-3" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                    <?php if (mysqli_num_rows($botines) > 0) {  ?>
                                        <?php do { ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                            <a href="shop-detail.php?id=<?php echo $botines1['id']; ?>">
                                                <div class="fruite-img">
                                                    <img src="<?php echo $botines1['img']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                                </div>
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4><?php echo $botines1['nombre']; ?></h4>
                                                    <p><?php echo $botines1['descripcion']; ?></p>
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-0">$<?php echo $botines1['precio']; ?></p>
                                                    <form class="add-to-cart-form" method="POST" action="">
                                                    <input type="hidden" name="id_producto" value="<?php echo $botines1['id']; ?>">
                                                    <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </a>
                                            </div>
                                        </div>
                                        <?php } while($botines1 = mysqli_fetch_array($botines));?>
                                        <?php } else { ?>
                                            <p>No hay productos en esta categoría.</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-4" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                    <?php if (mysqli_num_rows($sombreros) > 0) {  ?>
                                        <?php do { ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                            <a href="shop-detail.php?id=<?php echo $sombreros1['id']; ?>">
                                                <div class="fruite-img">
                                                    <img src="<?php echo $sombreros1['img']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                                </div>
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4><?php echo $sombreros1['nombre']; ?></h4>
                                                    <p><?php echo $sombreros1['descripcion']; ?></p>
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-0">$<?php echo $sombreros1['precio']; ?></p>
                                                    <form class="add-to-cart-form" method="POST" action="">
                                                    <input type="hidden" name="id_producto" value="<?php echo $sombreros1['id']; ?>">
                                                    <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </a>
                                            </div>
                                        </div>
                                        <?php } while($sombreros1 = mysqli_fetch_array($sombreros));?>
                                        <?php } else { ?>
                                            <p>No hay productos en esta categoría.</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-5" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                    <?php if (mysqli_num_rows($trajes) > 0) {  ?>
                                        <?php do { ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                            <a href="shop-detail.php?id=<?php echo $trajes1['id']; ?>">
                                                <div class="fruite-img">
                                                    <img src="<?php echo $trajes1['img']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                                </div>
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4><?php echo $trajes1['nombre']; ?></h4>
                                                    <p><?php echo $trajes1['descripcion']; ?></p>
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-0">$<?php echo $trajes1['precio']; ?></p>
                                                    <form class="add-to-cart-form" method="POST" action="">
                                                    <input type="hidden" name="id_producto" value="<?php echo $trajes1['id']; ?>">
                                                    <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </a>
                                            </div>
                                        </div>
                                        <?php } while($trajes1 = mysqli_fetch_array($trajes));?>
                                        <?php } else { ?>
                                            <p>No hay productos en esta categoría.</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
        <div class="container-fluid fruite py-5">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto text-danger">Notificación</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body text-dark">
                    Mensaje del Toast.
                </div>
            </div>
        </div>
    </div>

        </div>
                
        <div class="container-fluid vesitable py-5">
            <div class="container py-5">
                <h1 class="mb-0">Camisas Vaqueras
                </h1>
                <div class="owl-carousel vegetable-carousel justify-content-center">   
                    <?php if (mysqli_num_rows($camisas) > 0) {  ?>
                    <?php do { ?>
                    <div class="border border-primary rounded position-relative "> <!-- Le quite vesitable item-->
                        <div class="vesitable-img">

                            <img src="<?php echo $camisas1['img']; ?>" class="img-fluid w-100 rounded-top" alt="">
                        </div>
                        <div class="p-4 rounded-bottom">
                            <h4><?php echo $camisas1['nombre']; ?></h4>
                            <p><?php echo $camisas1['descripcion']; ?></p>
                           <div class="d-flex justify-content-between flex-lg-wrap">
                           <p class="text-dark fs-5 fw-bold mb-0">$<?php echo $camisas1['precio']; ?></p>
                            <form class="add-to-cart-form" method="POST" action="">
                            <input type="hidden" name="id_producto" value="<?php echo $camisas1['id']; ?>">
                            <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                            </button>
                            </form>
                            </div>
                        </div>
                    </div>
                    <?php } while($camisas1 = mysqli_fetch_array($camisas));?>
                    <?php } else { ?>
                        <p>No hay camisas disponibles, vuelva pronto</p>
                    <?php } ?>
                </div>

        
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-3 text-white">"Vive con el espíritu libre del oeste en cada paso."</h1>
                            <p class="fw-normal display-3 text-white-50 mb-4">Justo Ahora</p>
                            <p class="mb-4 text-white">"¡Ándele, compadre! Llévese la mera mera camisa del oeste estilo chingón y calidad a todo dar en una sola prenda."</p>
                            <a href="#" class="banner-btn btn border-2 border-white rounded-pill text-white py-3 px-5">Comprar</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="img/Camisa Promo.jpg" class="img-fluid w-100 rounded" alt="">
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                                <h1 style="font-size: 50px;">399</h1>
                                <div class="d-flex flex-column">
                                    <span class="h2 mb-0">$</span>
                                    <span class="h4 text-muted mb-0">c/u</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ->

           <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light p-5 rounded">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>Clientes Satisfechos</h4>
                                <h1>174</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>Calidad del Servicio</h4>
                                <h1>99%</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>Certificados de Calidad</h4>
                                <h1>10</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>Productos Disponibles</h4>
                                <h1>34</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>COMERCIALIZADORA LA OCASIÓN S.A DE C.V</a>, Todos los Derechos Reservados.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                         free as long as you keep the below author’s credit link/attribution link/backlink. ***/
                         use the template without the below author’s credit link/attribution link/backlink, ***/
                         the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/
                    </div>
                </div>
            </div>
        </div>
        
            <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
        console.log("DOM fully loaded and parsed");
        <?php if(isset($_SESSION['toast_message'])): ?>
            var toastEl = document.getElementById('liveToast');
            console.log("toastEl:", toastEl); 
            if (toastEl) {
                console.log("Toast element found");
                var toastBody = toastEl.querySelector('.toast-body');
                console.log("toastBody:", toastBody); 
                if (toastBody) {
                    toastBody.innerHTML = "<?php echo $_SESSION['toast_message']; ?>";

                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                    
                    <?php unset($_SESSION['toast_message']); ?>
                } else {
                    console.log("Toast body element not found");
                }
            } else {
                console.log("Toast element not found");
            }
        <?php endif; ?>
    });

        </script>
    </body>

</html>