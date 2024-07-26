<?php
session_start();
include 'conexion.php';


if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    
    // Obtener los datos del producto desde la base de datos
    $productoQuery = "SELECT * FROM productos WHERE id = '$id_producto'";
    $productoResult = mysqli_query($conexion, $productoQuery);
    $producto = mysqli_fetch_array($productoResult);
} else {
    // Redirigir a la página principal si no se proporciona un ID de producto
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id'];
    $id_producto = $_POST['id_producto'];
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
    $talla = $_POST['talla'];

    // Verificar que el id_producto y la talla no estén vacíos
    if (empty($id_producto) || empty($talla) || $cantidad <= 0) {
        echo "Datos incompletos.";
        exit();
    }

    // Verificar el stock disponible
    $stockQuery = "SELECT existencia FROM productos WHERE id = '$id_producto'";
    $stockResult = mysqli_query($conexion, $stockQuery);
    $stockData = mysqli_fetch_array($stockResult);
    $stock_disponible = $stockData['existencia'];

    if ($stock_disponible >= $cantidad) {
        // Verificar si el producto ya está en el carrito
        $checkCarritoQuery = "SELECT * FROM carrito_usuarios WHERE id_sesion='$id_usuario' AND id_producto='$id_producto' AND talla='$talla'";
        $result = mysqli_query($conexion, $checkCarritoQuery);

        if (mysqli_num_rows($result) > 0) {
            // Producto ya está en el carrito, actualizar la cantidad
            $updateQuery = "UPDATE carrito_usuarios SET cantidad = cantidad + '$cantidad' WHERE id_sesion='$id_usuario' AND id_producto='$id_producto' AND talla='$talla'";
            if (mysqli_query($conexion, $updateQuery)) {
                // Actualizar el stock en la tabla de productos
                $nuevoStock = $stock_disponible - $cantidad;
                $updateStockQuery = "UPDATE productos SET existencia = '$nuevoStock' WHERE id = '$id_producto'";
                mysqli_query($conexion, $updateStockQuery);

                $_SESSION['toast_message'] = 'Cantidad actualizada en el carrito';
            } else {
                echo "Error al actualizar la cantidad: " . mysqli_error($conexion);
            }
        } else {
            // Producto no está en el carrito, insertar nuevo registro con la cantidad seleccionada
            $insertQuery = "INSERT INTO carrito_usuarios (id_sesion, id_producto, talla, cantidad) VALUES ('$id_usuario', '$id_producto', '$talla', '$cantidad')";
            if (mysqli_query($conexion, $insertQuery)) {
                // Actualizar el stock en la tabla de productos
                $nuevoStock = $stock_disponible - $cantidad;
                $updateStockQuery = "UPDATE productos SET existencia = '$nuevoStock' WHERE id = '$id_producto'";
                mysqli_query($conexion, $updateStockQuery);

                $_SESSION['toast_message'] = 'Producto añadido al carrito';
            } else {
                echo "Error al añadir el producto: " . mysqli_error($conexion);
            }
        }
    } else {
        $_SESSION['toast_message'] = 'Stock insuficiente';
    }
    
    header('Location: shop-detail.php?id=' . $id_producto);
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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
                        <a href="login.php" class="my-auto">
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
            <h1 class="text-center text-white display-6">Detalles de productos</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Paginas</a></li>
                <li class="breadcrumb-item active text-white">Detalles de productos</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Single Product Start -->
        <div class="container-fluid py-5 mt-5">
            <div class="container py-5">
                <div class="row g-4 mb-5">
                    <div class="col-lg-8 col-xl-9">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded">
                                    <a href="#">
                                        <img  src="<?php echo $producto['img']; ?>" class="img-fluid rounded" alt="Image">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="fw-bold mb-3"><?php echo $producto['nombre']; ?></h4>
                                <p class="mb-3">Categoria: <?php echo $producto['categoria']; ?></p>
                                <h5 class="fw-bold mb-3"> $<?php echo $producto['precio']; ?></h5>
                                <div class="d-flex mb-4">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <p class="mb-4"><?php echo $producto['descripcion']; ?></p>
                                <form class="add-to-cart-form" method="POST" action="">
                                <div class="input-group quantity mb-5" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button id="buttonRes" class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input id="cantidad" name="cantidad" type="text" class="mb-5 form-control form-control-sm text-center border-0" value="1">
                                    <div class="input-group-btn">   
                                        <button id="buttonSum" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <td class="px-4">
                                    <label for="talla">Talla</label>
                                        <select id="talla" name="talla" class="border-0 form-select-sm bg-light me-3">
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                    </select>
                                </td>
                                </div>
                                
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                                    <button type="submit" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Agregar al carrito
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Description</button>
                                        <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                            aria-controls="nav-mission" aria-selected="false">Reviews</button>
                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <p> <?php echo $producto['descripcion']; ?></p>
                                        <div class="px-2">
                                            <div class="row g-4">
                                                <div class="col-6">
                                                    <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Color</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0">Hueso</p>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Estado de origen</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0">Morelos</p>
                                                        </div>
                                                    </div>
                                                    <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Calidad</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0">Organico    </p>
                                                        </div>
                                                    </div>
                                                    <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Peso</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0">250 mg</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                        <div class="d-flex">
                                            <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                            <div class="">
                                                <p class="mb-2" style="font-size: 14px;">Abril 12, 2024</p>
                                                <div class="d-flex justify-content-between">
                                                    <h5>José</h5>
                                                    <div class="d-flex mb-3">
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                </div>
                                                <p>Compré este sombrero de vaquero de fieltro beige para una boda temática western y superó todas mis expectativas.
                                                   La calidad del fieltro es excelente, se siente muy resistente y cómodo al mismo tiempo.  </p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                            <div class="">
                                                <p class="mb-2" style="font-size: 14px;">Abril 12, 2024</p>
                                                <div class="d-flex justify-content-between">
                                                    <h5>Juan Pérez</h5>
                                                    <div class="d-flex mb-3">
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                </div>
                                                <p class="text-dark">Lo he usado también para actividades al aire libre y es increíble cómo protege del sol sin perder estilo. 
                                                    La banda interior es muy suave y absorbente, lo que me permite llevarlo durante horas sin sentir ninguna incomodidad.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="nav-vision" role="tabpanel">
                                        <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                            amet diam et eos labore. 3</p>
                                        <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                            Clita erat ipsum et lorem et sit</p>
                                    </div>
                                </div>
                            </div>
                            <form action="#">
                                <h4 class="mb-5 fw-bold">Dejar un Comentario</h4>
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="border-bottom rounded">
                                            <input type="text" class="form-control border-0 me-4" placeholder="Tu nombre *">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="border-bottom rounded">
                                            <input type="email" class="form-control border-0" placeholder="Tu Email *">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="border-bottom rounded my-4">
                                            <textarea name="" id="" class="form-control border-0" cols="30" rows="8" placeholder="Tu comentario*" spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between py-3 mb-5">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0 me-3">Valora:</p>
                                                <div class="d-flex align-items-center" style="font-size: 12px;">
                                                    <i class="fa fa-star text-muted"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                            <a href="#" class="btn border border-secondary text-primary rounded-pill px-4 py-3"> Publicar comentario</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3">
                        <div class="row g-4 fruite">
                            <div class="col-lg-12">
                                <div class="input-group w-100 mx-auto d-flex mb-4">
                                    <input type="search" class="form-control p-3" placeholder="Filtro" aria-describedby="search-icon-1">
                                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                                </div>
                                <div class="mb-4">
                                    <h4>Categories</h4>
                                    <ul class="list-unstyled fruite-categorie">
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i>Botas</a>
                                                <span>(3)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i>Botines</a>
                                                <span>(5)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i>Sombreros</a>
                                                <span>(2)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i>Trajes Charros</a>
                                                <span>(8)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i>Trajes Regionales</a>
                                                <span>(5)</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <h4 class="mb-4">Productos mas valorados</h4>
                                <div class="col-lg-12">
                                    <h4 class="mb-3">Productos Característicos!</h4>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <div class="rounded me-4" style="width: 100px; height: 100px;">
                                            <img src="img/Botin 982.jpg" class="img-fluid rounded" alt="">
                                        </div>
                                        <div>
                                            <h6 class="mb-2">Botin Establo 982</h6>
                                            <div class="d-flex mb-2">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <h5 class="fw-bold me-2">799 $</h5>
                                                <h5 class="text-danger text-decoration-line-through">1.099 $</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <div class="rounded me-4" style="width: 100px; height: 100px;">
                                            <img src="img/Camisa Promo.jpg" class="img-fluid rounded" alt="">
                                        </div>
                                        <div>
                                            <h6 class="mb-2">Hannover Fantasia Hombre</h6>
                                            <div class="d-flex mb-2">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <h5 class="fw-bold me-2">499 $</h5>
                                                <h5 class="text-danger text-decoration-line-through"> 799$</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <div class="rounded me-4" style="width: 100px; height: 100px;">
                                            <img src="img/charro blanco.png" class="img-fluid rounded" alt="">
                                        </div>
                                        <div>
                                            <h6 class="mb-2">Traje Charro Hueso/Oro</h6>
                                            <div class="d-flex mb-2">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <h5 class="fw-bold me-2">1200 $</h5>
                                                <h5 class="text-danger text-decoration-line-through">1500$</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center my-4">
                                    <a href="#" class="btn border border-secondary px-4 py-3 rounded-pill text-primary w-100">Vew More</a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="position-relative">
                                    <img src="img/Jaripeo.jpg" class="img-fluid w-100 rounded" alt="">
                                    <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                        <h3 class="text-white fw-bold">Bueno <br> Bonito <br> Barato </h3>
                                    </div>
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
                            <div class="border border-primary rounded position-relative vesitable-item">
                                <div class="vesitable-img">
                                    <img src="img/camisa1.png" class="img-fluid w-100 rounded-top" alt="">
                                </div>
                                <div class="p-4 rounded-bottom">
                                    <h4>Hannover Bordada Blanca</h4>
                                    <p>Productos 100% Mexicanos</p>
                                   <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold mb-0">$950 </p>
        
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="border border-primary rounded position-relative vesitable-item">
                                <div class="vesitable-img">
                                    <img src="img/Camisa2.jpg" class="img-fluid w-100 rounded-top" alt="">
                                </div>
                                <div class="p-4 rounded-bottom">
                                    <h4>Hannover Bordada Beige</h4>
                                    <p>Productos 100% Mexicanos</p>
                                        <p class="text-dark fs-5 fw-bold mb-0">$950 </p>
                                        <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            
            </div>
        </div>
        <!-- Single Product End -->
    
        <div class="z-3 toast-container position-fixed bottom-0  end-0 p-3">
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
                            <a class="btn-link" href="">Detalles de productos</a>
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
    function updateQuantity(change) {
        var cantidadInput = document.getElementById('cantidad');
        var currentQuantity = parseInt(cantidadInput.value);

        if (!isNaN(currentQuantity)) {
            var newQuantity = currentQuantity + change;
            if (newQuantity > 0) { // Evitar cantidades negativas
                cantidadInput.value = newQuantity;
            }
        }
    }

    // Obtener los botones de incremento y decremento
    document.getElementById('buttonRes').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado
        updateQuantity(-1);
    });

    document.getElementById('buttonSum').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado
        updateQuantity(1);
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