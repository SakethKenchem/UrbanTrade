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
// no access if not logged in
if (!isset($_SESSION['username'])) {
    header('location: userlogin.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 50px;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
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
        .card {
            margin-bottom: 20px;
        }
        .nav-link {
            color: black;
        }
        .nav-item {
            margin-right: 10px;
        }
        .search-bar {
            width: 220px;
            margin-left: 50px;
        }
        .account-cart {
            display: flex;
            align-items: center;
        }
        .account-cart svg {
            margin-left: 8px;
        }
        .card{
            margin-top: 50px;
            width: 600px;
            margin-left: 400px;
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
                        <a class="nav-link" href="#userdetails">User Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#purchasehistory">Purchase History</a>
                    </li>

                </ul>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


<div class="card">
    <div class="card-header">
        <h5 id="userdetails">User Details</h5>
    </div>

    <div class="card-body">
        <form method="POST">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "urbantrade";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $username = $_POST['username'];
                $password = $_POST['password'];

                
                if (!empty($password)) {
                    
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updatePasswordQuery = "UPDATE users SET password='$hashedPassword' WHERE username='$username'";
                    if ($conn->query($updatePasswordQuery) === TRUE) {
                        echo "<div class='alert alert-success' role='alert'>Password updated successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Error updating password: " . $conn->error . "</div>";
                    }
                }

                
                $updateQuery = "UPDATE users SET email='$email', phonenumber='$phone', location='$address' WHERE username='$username'";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "<div class='alert alert-success' role='alert'>Details updated successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Error updating details: " . $conn->error . "</div>";
                }
            }

            
            $username = $_SESSION['username'];
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    
                    ?>
                    <div class="mb-3">
                        
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                    </div>
                    <div class="mb-3">
                        
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $row['phonenumber']; ?>">
                    </div>
                    <div class="mb-3">
                        
                        <label for="address" class="form-label">Address:</label>
                        <textarea class="form-control" id="address" name="address"><?php echo $row['location']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        
                        <label for="password" class="form-label">New Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                <?php
                }
            } else {
                echo "<p>No user found with this username.</p>";
            }
            $conn->close();
            ?>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 id="purchasehistory">Purchase History</h5>
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total Price</th>
                <th scope="col">Date Purchased</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "urbantrade";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            
            $username = $_SESSION['username'];
            $sql = "SELECT * FROM orders WHERE username = '$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row['productname']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['totalprice']; ?></td>
                        <td><?php echo $row['datepurchased']; ?></td>
                    </tr>
                <?php
                }
            } else {
                echo "<p>No purchase history found.</p>";
            }
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</div>


</body>

</html>
