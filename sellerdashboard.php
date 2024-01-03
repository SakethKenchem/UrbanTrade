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
// Make sure no one can access this page without logging in
if (!isset($_SESSION['seller_id'])) {
    header("Location: sellerlogin.php");
}

// Handle form submission for adding a product with multiple images
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $seller_name = $_SESSION['seller_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $seller_id = $_SESSION['seller_id'];

    // Insert product details into products table
    $sql = "INSERT INTO products (seller_id, name, description, price, category) VALUES ('$seller_id', '$name', '$description', '$price', '$category')";
    if ($conn->query($sql) === TRUE) {
        $product_id = $conn->insert_id; // Get the ID of the newly inserted product

        // Loop through uploaded images and insert their URLs into product_images table
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['images']['name'][$key];
            $image_tmp = $_FILES['images']['tmp_name'][$key];

            $target_dir = "productuploads/";
            $target_file = $target_dir . basename($image_name);

            // Move uploaded file to target directory
            if (move_uploaded_file($image_tmp, $target_file)) {
                $image_url = $target_file;

                // Insert image URL into product_images table
                $sql = "INSERT INTO product_images (product_id, image_url) VALUES ('$product_id', '$image_url')";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error inserting image: " . $conn->error;
                }
            } else {
                echo "Failed to upload $image_name.";
            }
        }
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// logic to delete a product
if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];
    $sql = "DELETE FROM products WHERE product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        //javascript alert confirmation to delete product
        echo "<script>alert('Product deleted successfully!');</script>";
        header("Location: sellerdashboard.php");
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// logic to handle search feature
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $seller_id = $_SESSION['seller_id'];
    $search = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND (name LIKE ? OR description LIKE ? OR category LIKE ?)");
    $stmt->bind_param("isss", $seller_id, $search, $search, $search);
    $stmt->execute();
    $products_result = $stmt->get_result();

    if ($products_result->num_rows === 0) {
        
    }
} else {
    $seller_id = $_SESSION['seller_id'];
    $products_sql = "SELECT * FROM products WHERE seller_id = '$seller_id'";
    $products_result = $conn->query($products_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        .card .btn{
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            margin: 0.3rem;
        }
    </style>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="sellerdashboard.php">
            <img img src="images/Urban Trade KE logo.jpeg" alt="Urban Trade Logo" height="40" width="40" class="d-inline-block align-text-top me-2">    
    
            Urban Trade KE Seller Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#AddProducts">Add Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#MyProducts">My Products</a>
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

    <h1>Welcome, <?php echo $_SESSION['seller_name']; ?> 👋!</h1>


<div class="mt-5 mb-5" id="AddProducts">
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data" class="border p-4 rounded">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="name" placeholder="Enter Product Name">
        </div>
        <div class="mb-3">
            <label for="productDescription" class="form-label">Description</label>
            <textarea class="form-control" id="productDescription" name="description" rows="3" placeholder="Enter Description"></textarea>
        </div>
        <div class="mb-3">
            <label for="productPrice" class="form-label">Price</label>
            <input type="text" class="form-control" id="productPrice" name="price" placeholder="Enter Price">
        </div>
        <div class="mb-3">
            <label for="productCategory" class="form-label">Category</label>
            <select class="form-select" id="productCategory" name="category">
                <option value="Electronics">Electronics</option>
                <option value="Fashion">Fashion</option>
                <option value="Home">Home</option>
                <option value="Beauty">Beauty</option>
                <option value="Sports">Sports</option>
                <option value="Books">Books</option>
                <option value="Toys">Toys</option>
                <option value="Food">Food</option>
                <option value="Stationery">Stationery</option>
                <option value="Furniture">Furniture</option>
                <option value="Health">Health</option>
                <option value="Baby Products">Baby Products</option>
                <option value="Automotive">Automotive</option>
                <option value="Pet Supplies">Pet Supplies</option>
                <option value="Industrial">Industrial</option>
                <option value="Movies">Movies</option>
                <option value="Music">Music</option>
                <option value="Garden">Garden</option>
                <option value="Tools">Tools</option>
                <option value="Grocery">Grocery</option>
                <option value="Jewelry">Jewelry</option>
                <option value="Shoes">Shoes</option>
                <option value="Handmade">Handmade</option>
                <option value="Software">Software</option>
                <option value="Video Games">Video Games</option>
                <option value="Arts">Arts</option>
                <option value="Collectibles">Collectibles</option>
                <option value="Digital Media">Digital Media</option>
                <option value="Appliances">Appliances</option>
                <option value="Luggage">Luggage</option>
                <option value="Musical Instruments">Musical Instruments</option>
                <option value="Office Products">Office Products</option>
                <option value="Watches">Watches</option>
                <option value="Software">Software</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="productImage" class="form-label">Image</label>
            <input type="file" class="form-control" id="productImage" name="images[]" multiple>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<div class="mt-5" id="MyProducts">
    <h2>My Products</h2>

    <form method="GET" action="sellerdashboard.php">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for products" aria-label="Search for products" aria-describedby="button-addon2" name="search">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-md-4 g-4">
    <?php
    if ($products_result->num_rows > 0) {
        while ($product_row = $products_result->fetch_assoc()) {
            echo "<div class='col'>";
            echo "<div class='card h-90'>"; // Set a fixed height for cards in each column
            echo "<div class='card-body'>";
            // Carousel for multiple images within the same card
            echo "<div id='productCarousel{$product_row['product_id']}' class='carousel slide' data-bs-ride='carousel'>";
            echo '<div class="carousel-inner">';
            $firstImage = true;

            // Fetch and display images within the carousel
            $images_sql = "SELECT image_url FROM product_images WHERE product_id = '{$product_row['product_id']}'";
            $images_result = $conn->query($images_sql);

            while ($image_row = $images_result->fetch_assoc()) {
                $activeClass = $firstImage ? 'active' : ''; // Set the active class for the first image
                echo "<div class='carousel-item $activeClass'>";
                echo "<img src='" . $image_row['image_url'] . "' class='d-block w-100' style='height: 250px; object-fit: cover;' alt='Product Image'>";
                echo "</div>";
                $firstImage = false;
            }

            echo '</div>';
            echo '<button class="carousel-control-prev" type="button" data-bs-target="#productCarousel' . $product_row['product_id'] . '" data-bs-slide="prev">';
            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Previous</span>';
            echo '</button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#productCarousel' . $product_row['product_id'] . '" data-bs-slide="next">';
            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Next</span>';
            echo '</button>';
            echo "</div>";

            echo "<h5 class='card-title'>" . $product_row['name'] . "</h5>";
            echo "<p class='card-text'>" . substr($product_row['description'], 0, 45) . (strlen($product_row['description']) > 45 ? '...' : '') . "</p>";
            // id of product
            echo "<p class='card-text'><small class='text-muted'>Product ID: " . $product_row['product_id'] . "</small></p>";
            echo "<p class='card-text'><small class='text-muted'>Date: " . $product_row['created_at'] . "</small></p>";

            // View, Edit, and Delete buttons
            echo '<div class="btn-group">';
            echo "<a href='productdetails.php?product_id=" . $product_row['product_id'] . "' class='btn btn-primary'><i class='fas fa-eye'></i></a>";
            echo "<a href='editproduct.php?product_id=" . $product_row['product_id'] . "' class='btn btn-secondary'><i class='fas fa-edit'></i></a>";
            echo "<a href='sellerdashboard.php?delete_product_id=" . $product_row['product_id'] . "' class='btn btn-danger'><i class='fas fa-trash'></i></a>";
            echo "</div>";

            echo "</div></div>";
            echo "</div>";
        }
    } else {
        echo "<div class='alert alert-info' role='alert'>No products found.</div>";
    }
    ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
