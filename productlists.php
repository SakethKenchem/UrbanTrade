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
$category = isset($_GET['category']) ? $_GET['category'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?php echo $searchQuery; ?></title>
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Your CSS styles here */
    </style>
</head>

<body>
    <div style="display: flex; align-items:left;">
        <a href="homepage.php" class="btn btn-primary">Back to Homepage</a>
    </div>
    <div class="container">
        <h2>Results</h2>
        <div class="row">
            <?php
            $query = "SELECT * FROM products";

            if (!empty($searchQuery)) {
                $query .= " WHERE name LIKE '%$searchQuery%'";
            }

            if (!empty($category)) {
                if (strpos($query, 'WHERE') === false) {
                    $query .= " WHERE category = '$category'";
                } else {
                    $query .= " AND category = '$category'";
                }
            }

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="col-md-4 mb-4">
                        <!-- Display product details -->
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="img-fluid">
                        <h3><?php echo $row['name']; ?></h3>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        <p>Category: <?php echo $row['category']; ?></p>
                        <!-- View product details bootstrap button -->
                        <button type="button" class="btn btn-primary">
                            <a href="productdetails.php?product_id=<?php echo $row['product_id']; ?>" style="color: white; text-decoration: none;">
                                View Details
                            </a>
                        </button>
                    </div>
            <?php
                }
            } else {
                echo "<h3>No products found</h3>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close();
?>
