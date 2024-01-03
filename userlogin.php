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
    // Handle login
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                header("Location: homepage.php"); // Redirect after successful login
                exit();
            } else {
                $errorMessage = "Invalid username/email or password";
            }
        } else {
            $errorMessage = "Invalid username/email or password";
        }

        $stmt->close();
    } else {
        $errorMessage = "Error in preparing login statement: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="Urban Trade KE logo.jpeg" type="image/gif" sizes="16x16">
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
          <a class="navbar-brand" style="font-size: x-large;" href="homepage.php">Urban Trade KE</a>
        </div>
    </nav>
    <div class="grid">
        <h1>Login</h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16" style="margin-top: 5px; margin-bottom: 5px;">
            <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
            <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
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
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password">
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
            <div>
                <a href="usersignup.php" class="btn btn-secondary" style="margin-top: 5px;">Signup</a>
            </div>
        </form>
    </div>
</body>
</html>

