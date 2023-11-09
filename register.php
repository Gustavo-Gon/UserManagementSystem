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
    <table>
        <tr>
            <td class="title" colspan="2">Registration Form</td>
        </tr>
        <tr>
            <td><label for="username">Username:</label></td>
            <td><input type="text" name="username" id="username" maxlength="50" value="<?php echo $username; ?>"></td>
        </tr>
        <tr>
            <td><label for="password">Password:</label></td>
            <td><input type="password" name="password" id="password" maxlength="9"></td>
        </tr>
        <tr>
            <td><label for="confirm_password">Confirm Password:</label></td>
            <td><input type="password" name="confirm_password" id="confirm_password" maxlength="9"></td>
        </tr>
        <tr>
            <td><label for="fullname">Full Name:</label></td>
            <td><input type="text" name="fullname" id="fullname" maxlength="50"></td>
        </tr>
        <tr>
            <td><label for="dob">Date of Birth:</label></td>
            <td><input type="date" name="dob" id="dob"></td>
        </tr>
        <tr>
            <td><label for="gender">Gender:</label></td>
            <td>
                <input type="radio" id="gender" name="gender" value="1">
                <label for="Male">Male</label>
                
                <input type="radio" id="gender" name="gender" value="2">
                <label for="Female">Female</label>
                
                <input type="radio" id="gender" name="gender" value="0">
                <label for="Other">Other</label>
            </td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" name="email" id="email" maxlength="50"></td>
        </tr>
        <tr>
            <td><label for="mobile">Mobile:</label></td>
            <td><input type="tel" name="mobile" id="mobile" pattern="[0-9]{3}[0-9]{3}[0-9]{4}"></td>
        </tr>
        <tr>
            <td><label for="address">Address:</label></td>
            <td><textarea name="address" id="address" rows="4" maxlength="200"></textarea></td>
        </tr>
        <tr>
        <td><label for="state">State:</label></td>
            <td>
                <select name="state" id="state">
                    <option value="WA">WA</option>
                    <option value="OR">OR</option>
                    <option value="CA">CA</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="city">City:</label></td>
            <td><input type="text" name="city" id="city" maxlength="20"></td>
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
