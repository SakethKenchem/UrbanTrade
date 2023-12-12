<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar bg-body-none">
        <div class="container-fluid">
          <a class="navbar-brand" style="font-size: x-large;">Urban Trade KE</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Seller Signup</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="seller_name" class="form-label">Seller Name</label>
                        <input type="text" class="form-control" id="seller_name" name="seller_name" placeholder="Enter Seller Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter Phone Number">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="seller_signup">Signup</button>
                    <div class="mt-3">
                        Already have an account? <a href="sellerlogin.php">Login</a>
                    </div>
                </form>

                <?php
                if (isset($_POST['seller_signup'])) {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "urbantrade";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $seller_name = $_POST['seller_name'];
                    $email = $_POST['email'];
                    $phone_number = $_POST['phone_number'];
                    $address = $_POST['address'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

                    $sql = "INSERT INTO sellers (seller_name, email, phone_number, address, password) 
                            VALUES ('$seller_name', '$email', '$phone_number', '$address', '$password')";

                    if ($conn->query($sql) === TRUE) {
                        echo '<div class="alert alert-success mt-3" role="alert">Signup successful!</div>';
                        header("refresh:2; url=sellerlogin.php");
                    } else {
                        echo '<div class="alert alert-danger mt-3" role="alert">Error: ' . $conn->error . '</div>';
                    }

                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
