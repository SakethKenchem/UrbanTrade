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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $seller_id = $_SESSION['seller_id'];

    // File upload handling
    $target_dir = "productuploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ... (same file upload validation as before)

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
            $sql = "INSERT INTO products (seller_id, name, description, price, image_url) VALUES ('$seller_id', '$name', '$description', '$price', '$image_url')";
            if ($conn->query($sql) === TRUE) {
                echo "Product added successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Your custom styles here */
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="sellerdashboard.php">
            <img img src="Urban Trade KE logo.jpeg" alt="Urban Trade Logo" height="40" width="40" class="d-inline-block align-text-top me-2">    
    
            Urban Trade KE Seller Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                </ul>
                <!--sellerlogout.php-->
                <form class="d-flex" method="POST" action="sellerlogout.php">
                    <button class="btn btn-outline-success" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

    <h1>Welcome, <?php echo $_SESSION['seller_name']; ?>!</h1>


<!-- Product addition form -->
<div class="mt-5 mb-5">
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data" class="border p-4 rounded">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="name">
        </div>
        <div class="mb-3">
            <label for="productDescription" class="form-label">Description</label>
            <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="productPrice" class="form-label">Price</label>
            <input type="text" class="form-control" id="productPrice" name="price">
        </div>
        <div class="mb-3">
            <label for="productImage" class="form-label">Image</label>
            <input type="file" class="form-control" id="productImage" name="image">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>


        <!-- Display seller's products -->
        <div class="mt-5">
            <h2>My Products</h2>
            <?php
            $seller_id = $_SESSION['seller_id'];
            $products_sql = "SELECT * FROM products WHERE seller_id = '$seller_id'";
            $products_result = $conn->query($products_sql);

            if ($products_result->num_rows > 0) {
                while ($product_row = $products_result->fetch_assoc()) {
                    echo "<div class='card' style='width: 18rem; display: inline-block; margin: 10px;'>";
                    echo "<img src='" . $product_row['image_url'] . "' class='card-img-top' alt='Product Image'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $product_row['name'] . "</h5>";
                    echo "<p class='card-text'>" . $product_row['description'] . "</p>";
                    echo "<p class='card-text'>$" . $product_row['price'] . "</p>";
                    echo "</div></div>";
                }
            } else {
                echo "No products found.";
            }
            ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
