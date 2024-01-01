<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbantrade";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchQuery)) {
    $stmt = $conn->prepare("SELECT p.product_id FROM products p WHERE p.name LIKE ?");
    $searchParam = "%{$searchQuery}%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        header("Location: productdetails.php?product_id=" . $row['product_id']);
        exit();
    } else {
        echo "No search results found";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!--favicon-->
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            body{
                background-color: #f5f5f5;
                margin-bottom: 100px;
            }
            .navbar {
                background-color: #fff;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 2px;
            }
            .navbar .navbar-brand {
                font-weight: 600;
                padding-left: 20px;
            }
            .navbar .navbar-brand img {
                margin-right: 5px;
            }
            .navbar .navbar-nav li.nav-item {
                margin-right: 10px;
            }
            .navbar .navbar-nav li.nav-item a.nav-link {
                font-size: 16px;
                font-weight: 500;
            }
            .navbar .navbar-nav li.nav-item a.nav-link i {
                margin-right: 5px;
            }
            .navbar .navbar-nav li.nav-item a.nav-link:hover i {
                color: #2bbc8a;
            }
            .navbar .navbar-nav li.nav-item a.nav-link:hover {
                color: #2bbc8a;
            }
            .nav-link {
                color: black;
            }
            .nav-item {
                margin-right: 10px;
            }
            .search-bar {
                width: 230px;
                margin-left: 50px;
            }
            .account-cart {
                display: flex;
                align-items: center;
            }
            .account-cart svg {
                margin-left: 8px;
            }

            .featured-products {
                display: flex;
                flex-wrap: wrap;
                justify-content: left;
                margin: 20px 0;
            }

            .featured-product-card {
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 10px;
                width: 300px; 
                margin: 10px;
                text-align: center;
                overflow: hidden; 
                text-overflow: ellipsis; 
                white-space: nowrap; 
            }
            .featured-product-card:hover {
                border-color: #999;
                box-shadow: 0 0 10px #999;
                transform: scale(1.03);

            }
            .featured-product-img {
                max-width: 100%;
                height: 120px;
                overflow: hidden;
            }
            .featured-product-card .btn-secondary {
                display: none;
            }
            .featured-product-card:hover .btn-secondary {
                display: block;
                margin-top: 5px;
            }
            @keyframes ticker {
                    0% { transform: translateX(100%); }
                    100% { transform: translateX(-100%); }
                }

                .ticker-text {
                    display: inline-block;
                    white-space: nowrap;
                    overflow: hidden;
                    position: relative;
                    animation: ticker linear infinite;
                }
                .ticker-text:hover {
                    animation-play-state: paused;
                }
                
        </style>
</head>
<body>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="homepage.php" style="margin-top: 4px;">
            <img src="Urban Trade KE logo.jpeg" alt="Urban Trade Logo" height="40" width="40" class="d-inline-block align-text-top me-2">
            Urban Trade KE
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="productcategories.php">Product Categories</a>
                </li>
            </ul>
            
            <form class="d-flex" action="productlists.php" method="GET">
                <input class="form-control me-2 search-bar" type="search" placeholder="Search" aria-label="Search" name="search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <section class="featured-products">
                <?php
                    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

                    if (!empty($searchQuery)) {
                        $stmt = $conn->prepare("SELECT p.product_id, p.name FROM products p WHERE p.name LIKE ?");
                        $searchParam = "%{$searchQuery}%";
                        $stmt->bind_param("s", $searchParam);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<a href="productdetails.php?product_id=' . $row['product_id'] . '">' . $row['name'] . '</a><br>';
                            }
                        } else {
                            echo "No search results found";
                        }

                        $stmt->close();
                    }
                ?>
            </section>
        </div>
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['username'])) : ?>
                <li class="nav-item">
                    <a href="myaccount.php" class="nav-link"><?php echo $_SESSION['username']; ?></a>
                </li>
                <li class="nav-item">
                    <a href="userlogout.php" class="nav-link">Logout</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a href="userlogin.php" class="nav-link">Login</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="cart.php" class="nav-link">
                    <div class="account-cart">
                        <span>Cart</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</nav>


<!-- Carousel -->
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="onlineshopping.jpg" class="d-block w-100" alt="..." style="width: 400px; height:480px;">
    </div>
    <div class="carousel-item">
      <img src="s23.jpg" class="d-block w-100" alt="..." style="width: 400px; height:480px;">
    </div>
    <div class="carousel-item">
      <img src="flashsales.jpg" class="d-block w-100" alt="..." style="width: 400px; height:480px;">
    </div>
    <div class="carousel-item">
      <img src="lg.jpg" class="d-block w-100" alt="..." style="width: 400px; height:480px;">
    </div>
    <div class="carousel-item">
      <img src="lgmonitor.webp" class="d-block w-100" alt="..." style="width: 400px; height:450px;">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbantrade";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.*, pi.image_url AS product_image FROM products p 
LEFT JOIN product_images pi ON p.product_id = pi.product_id 
INNER JOIN sellers s ON p.seller_id = s.seller_id LIMIT 5"; 
$result = $conn->query($sql);

?>
<div style="margin-left: 30px; margin-top: 10px;">
    <h2>Featured Products</h2>
</div>  

<section class="featured-products">
    <?php

$sql = "SELECT p.product_id, p.name, p.description, p.price, s.seller_name, pi.image_url AS product_image FROM products p 
LEFT JOIN product_images pi ON p.product_id = pi.product_id 
INNER JOIN sellers s ON p.seller_id = s.seller_id LIMIT 5";
$result = $conn->query($sql);

$displayedProducts = []; // Keep track of displayed product IDs

if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$product_id = $row['product_id'];

// Check if the product has already been displayed
if (!in_array($product_id, $displayedProducts)) {
    $displayedProducts[] = $product_id; // Add product ID to displayed list

    ?>
    <div class="featured-product-card">
        <?php
        // Image carousel
        $image_sql = "SELECT image_url FROM product_images WHERE product_id = $product_id";
        $image_result = $conn->query($image_sql);

        if ($image_result->num_rows > 0) {
            ?>
            <div id="carousel_<?php echo $product_id; ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $count = 0;
                    while ($image_row = $image_result->fetch_assoc()) {
                        $active_class = ($count == 0) ? 'active' : '';
                        ?>
                        <div class="carousel-item <?php echo $active_class; ?>">
                            <img src="<?php echo $image_row['image_url']; ?>" class="d-block w-100" alt="Product Image">
                        </div>
                        <?php
                        $count++;
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel_<?php echo $product_id; ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel_<?php echo $product_id; ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <?php
        }

        ?>
        <h5><?php echo $row['name']; ?></h5>
        <p style="font-size: small;"><b>Desc.: </b><?php echo strlen($row['description']) > 35 ? substr($row['description'], 0, 25) . '...' : $row['description']; ?></p>
        <p><b>Price: </b>Ksh. <?php echo $row['price']; ?></p>
        <p><b>Seller:</b> <?php echo $row['seller_name']; ?></p>
        <a href="productdetails.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary">View Details</a>
        <button class="btn btn-secondary" style="margin-left: 85px;">Add to Cart</button>
    </div>
    <?php
}
}
} else {
echo "No featured products available";
}
    ?>
</section>

<?php
$conn->close(); 
?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.featured-product-card h5').each(function() {
                let $h5 = $(this);
                let originalText = $h5.text().trim();
                let $tickerText = $h5.wrapInner('<span class="ticker-text"></span>').find('.ticker-text');

                $tickerText.css('width', $tickerText.width()); // Ensure text doesn't break due to width change on hover

                function startTicker() {
                    $tickerText.css('animation-duration', ($tickerText.width() / 100) + 's');
                }

                function stopTicker() {
                    $tickerText.css('animation-duration', '0s');
                }

                $h5.parent('.featured-product-card').hover(startTicker, stopTicker);
            });
        });
    </script>
</body>
</html>
