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
</head>
<body>
    <h2>Login Form</h2>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td><label for="username">Username:</label></td>
                <td><input type="text" id="username" name="username"></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="password"></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"></td>
                <td><a href="index.php">Back to Home</a></td>
            </tr>
        </table>
    </form>
</body>
</html>
<?php
}
?>
