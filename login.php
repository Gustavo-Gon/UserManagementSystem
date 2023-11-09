<?php
// Start the session
session_start();

// Variable to keep track of login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Function to connect to the database
function connectToDB() {
    $database = new PDO('sqlite:account.db');
    return $database;
}

// Function to check login credentials
function checkLogin($username, $password) {
    try {
        $db = connectToDB();
        $stmt = $db->prepare("SELECT * FROM account WHERE username = :username AND password = :password");
        $stmt->execute([':username' => $username, ':password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form was submitted
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Check if all fields are filled out
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        // Attempt to login
        $user = checkLogin($username, $password);

        if ($user) {
            // Successful login
            $_SESSION['fullname'] = $user['fullname'];
            header('Location: manage.php');
            exit;
        } else {
            // Login failed
            $_SESSION['login_attempts']++;
            echo "Login failed. Attempt #" . $_SESSION['login_attempts'];
        }
    }
} else {
    // Form not submitted, show the form (GET request)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <form action="login.php" method="post">
        <table class="table">
            <tr>
                <td colspan="2" class="title">Login Form</td>
            </tr>
            <tr>
                <td><label for="username">Username</label></td>
                <td><input type="text" id="username" name="username"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" id="password" name="password"></td>
            </tr>
            <tr>
                <td></td> <!-- Empty column -->
                <td class="button-container">
                    <a href="index.php" class="back-link">Back</a>
                    <input type="submit" value="Login" class="login-button">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
<?php
}
?>
