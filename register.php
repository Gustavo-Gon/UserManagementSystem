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

    // Validate username
    if (empty($_POST["username"])) {
        $errors['username'] = "Username is required";
    } else {
        $username = sanitizeInput($_POST["username"]);

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

    // Vaidate full name 
    if(empty($_POST["fullname"])) {
        $errors['fullname'] = "Full name is required";
    } else {
        $fullname = sanitizeInput($_POST["fullname"]);
    }

    // Validate date of birth 
    if(empty($_POST["dob"])) {
        $errors['dob'] = "Date of Birth is required";
    } else {
        $dob = sanitizeInput($_POST["dob"]);
    }

    // Validate gender
    if(empty($_POST["gender"])) {
        $errors['gender'] = "Gender is required";
    } else {
        $gender = sanitizeInput($_POST["gender"]);
    }

    // Validate email
    if(empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } else {
        $email = sanitizeInput($_POST["email"]);
    }

    // Validate mobile
    if(empty($_POST["mobile"])) {
        $errors['mobile'] = "Mobile is required";
    } else {
        $mobile = sanitizeInput($_POST["mobile"]);
    }
    // Validate address
    if(empty($_POST["address"])) {
        $errors['address'] = "Address is required";
    } else {
        $address = sanitizeInput($_POST["address"]);
    }

    // Validate state
    if(empty($_POST["state"])) {
        $errors['state'] = "State is required";
    } else {
        $state = sanitizeInput($_POST["state"]);
    }

    // Validate city
    if(empty($_POST["city"])) {
        $errors['city'] = "City is required";
    } else {
        $city = sanitizeInput($_POST["city"]);
    }

    // If there are no errors, insert into the database
    if (count($errors) === 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO account (username, password, fullname, dob, gender, email, mobile, address, state, city, permission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $fullname, $dob, $gender, $email, $mobile, $address, $state, $city, 0]);
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
    <link rel="stylesheet" href="index.css">
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
            <input type="text" name="username" id="username" maxlength="50" value="<?php echo $username; ?>">
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" maxlength="9">
        </p>
        <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" maxlength="9">
        </p>
        <p>
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" id="fullname" maxlength="50">
        </p>
        <p>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob">
        </p>
        <p>
        <input type="radio" id="gender" name="gender" value="1">
        <label for="Male">Male</label><br>

        <input type="radio" id="gender" name="gender" value="2">
        <label for="Female">Female</label><br>

        <input type="radio" id="gender" name="gender" value="0">
        <label for="Other">Other</label>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" maxlength="50">
        </p>
        <p>
            <label for="mobile">Mobile:</label>
            <input type="tel" name="mobile" id="mobile" pattern="[0-9]{3}[0-9]{3}[0-9]{4}">
        </p>
        <p>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" maxlength="200">
        </p>
        <p>
            <label for="state">State:</label>
            <input type="text" name="state" id="state" minlength="2" maxlength="2">
        </p>
        <p>
            <label for="city">City:</label>
            <input type="text" name="city" id="city" maxlength="20">
        </p>
        <p>

            <input type="submit" value="Register">
        </p>
    </form>
</body>
</html>
<?php
}
?>
