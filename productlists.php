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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .product-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            width: 320px;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: scale(1.03);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items:left;">
        <a href="homepage.php" class="btn btn-primary">Back to Homepage</a>
    </div>
    <div class="container mt-5">
        <h2>Products containing '<?php echo $searchQuery; ?>'</h2>
        <div class="row">
            <?php
            if (!empty($searchQuery)) {
                $stmt = $conn->prepare("SELECT p.product_id, p.name, p.image_url FROM products p WHERE p.name LIKE ?");
                $searchParam = "%{$searchQuery}%";
                $stmt->bind_param("s", $searchParam);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="product-card">
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="featured-product-img">
                                <h5><?php echo $row['name']; ?></h5>
                                <!-- Add more product details here -->
                                <a href="productdetails.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-secondary">View Details</a>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo "<h2>No products found containing '$searchQuery'</h2>";
                }

                $stmt->close();
            }
            ?>
        </div>
        <!--button to go back to homepage.php-->
        

    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close();
?>
