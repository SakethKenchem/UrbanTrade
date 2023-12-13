
<?php
session_name("adminsession");
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbantrade";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $firstname = $_POST['first_name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE email = ? ";
    $stmt = $conn->prepare($sql);


    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['first_name'] = $row['first_name'];
                header("Location: admindashboard.php"); // Redirect after successful login
                exit();
            } else {
                $errorMessage = "Invalid email or password";
                header("adminlogin.php");
            }
        } else {
            $errorMessage = "Invalid email or password";
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
    <title>Admin Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Admin Signup</h2>
                <form action="adminlogin.php" method="POST">
                <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='adminsignup.php'">Signup</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>