<?php
session_start();

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$errors = [];
$username = $password = $confirm_password = $fullname = $dob = $gender = $email = $mobile = $address = $state = $city = "";

// SQLite database path
$dbPath = 'account.db';

try {
    // Create a PDO instance as db connection to SQLite
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    // Add your input validation here
    
    // Validate username
    if (empty($_POST["username"])) {
        $errors['username'] = "Username is required";
    } else {
        $username = sanitizeInput($_POST["username"]);
        // Additional validation can be added here (like checking if username already exists in the database)
    }

    // Validate password and confirm password
    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required";
    } else {
        $password = sanitizeInput($_POST["password"]);
        if (empty($_POST["confirm_password"]) || $_POST["confirm_password"] !== $password) {
            $errors['confirm_password'] = "The passwords do not match";
        }
    }

    // ... similarly validate other fields

    // If there are no errors, insert into the database
    if (count($errors) === 0) {
        // Hash the password before storing - IMPORTANT for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO account (username, password, fullname, dob, gender, email, mobile, address, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $fullname, $dob, $gender, $email, $mobile, $address, $state, $city]);
            $_SESSION['message'] = 'Registration successful!';
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $errors['database'] = $e->getMessage();
        }
    }
}

// Show form if GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" || !empty($errors)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Add your CSS link here -->
</head>
<body>
    <h2>Registration Form</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <form action="register.php" method="post">
        <!-- Input fields for registration -->
        <!-- Display errors if any -->
        <?php
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $username; ?>">
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </p>
        <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </p>
        <!-- Add other fields similarly -->
        <p>
            <input type="submit" value="Register">
        </p>
    </form>
</body>
</html>
<?php
}
?>
