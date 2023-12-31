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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container {
            display: flex;
            justify-content: left;
            flex-wrap: wrap;
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
        /*make sure all the images are the same size*/
        .img-fluid {
            width: 100%px;
            height: 250px;
        }


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="homepage.php" style="margin-top: 4px;">
            <img img src="Urban Trade KE logo.jpeg" alt="Urban Trade Logo" height="40" width="40" class="d-inline-block align-text-top me-2">    
            Urban Trade KE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="productcategories.php">Product Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown link
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2 search-bar" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
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
        </div>
    </nav>
    
<?php
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "SELECT p.*, pi.image_url AS image, s.seller_name FROM products p 
            INNER JOIN product_images pi ON p.product_id = pi.product_id 
            INNER JOIN sellers s ON p.seller_id = s.seller_id 
            WHERE p.product_id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <h2><?php echo $row['name']; ?></h2>
                    <p><b>Price: </b>Ksh. <?php echo $row['price']; ?></p>
                    <p><b>Category: </b><?php echo $row['category']; ?></p>
                    <p><b>Description: </b><?php echo $row['description']; ?></p>
                    <p><b>Seller: </b><?php echo $row['seller_name']; ?></p>
                    <button class="btn btn-primary">Add to Cart</button>
                    <div style="margin-top: 5px;">
                        <a href="homepage.php">
                            <button class="btn btn-success">Back</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "No product found";
    }
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>