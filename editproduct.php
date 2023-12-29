<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            margin-bottom: 50px;
        }
        /*add a border aroiund the image and make space below it*/
        img{
            border: 2px solid black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbantrade"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form action="updateproduct.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"><?php echo $row['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $row['price']; ?>">
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo $row['category']; ?>">
            </div>
            <div class="form-group">
                <label for="image_url">Current Image:</label><br>
                <img src="<?php echo $row['image_url']; ?>" alt="Product Image" style="max-width: 200px;">
            </div>
            <div class="form-group">
                <label for="new_image">New Image:</label>
                <input type="file" class="form-control-file" id="new_image" name="new_image">
            </div>
            <div style="margin-top: 12px">
            <button type="submit" class="btn btn-primary" >Update Product</button>
            <button type="reset" class="btn btn-danger">Reset</button>
            <a href="sellerdashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
<?php
    } else {
        echo "Product not found.";
    }
}
$conn->close();
?>

</body>
</html>
