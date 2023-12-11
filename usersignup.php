<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbantrade";

// Establishing a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle signup
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phonenumber = $_POST['phonenumber'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, phonenumber) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $phonenumber);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redirect after successful signup
            exit();
        } else {
            $errorMessage = "Error in signup: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Error in preparing signup statement: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
            .grid{
                display: grid;
                place-items: center;
                margin-top: 65px;
                border-radius: 5px;
                padding: 20px;
                width: 400px;
                margin-left: auto;
                margin-right: auto;
            }
            .form{
                width: 300px;
                
            }
        </style>
</head>
<body>
    <nav class="navbar bg-body-none">
        <div class="container-fluid">
          <a class="navbar-brand" style="font-size: x-large;">Urban Trade KE</a>
        </div>
    </nav>
    <div class="grid">
        <h1>Signup</h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-clipboard-data-fill" viewBox="0 0 16 16" style="margin-top: 5px; margin-bottom: 5px;">
            <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zM10 8a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zm-6 4a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm4-3a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1"/>
        </svg>
        <?php if (isset($errorMessage)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="form">
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username">
            </div>
            <div class="mb-3">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address">
            </div>
            <div class="mb-3">
                <label for="phonenumber">Phone Number</label>
                <input type="tel" name="phonenumber" id="phonenumber" class="form-control" placeholder="Enter Phone Number">
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password">
            </div>
            <button type="submit" name="signup" class="btn btn-primary">Signup</button>
            <div>
                <a href="userlogin.php" class="btn btn-secondary" style="margin-top: 5px;">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
