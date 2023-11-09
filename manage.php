<?php
session_start();
$_SESSION['permission'] = 1;
// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_SESSION['permission']) && $_SESSION['permission'] == 1) {
    // Database connection (replace with your database configuration)
    $pdo = new PDO("sqlite:account.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete user if "id" is provided in the GET request
    if (isset($_GET['id'])) {
        $id = sanitizeInput($_GET['id']);
        $deleteStmt = $pdo->prepare("DELETE FROM account WHERE id = ?");
        $deleteStmt->execute([$id]);
        header("Location: manage.php");
        exit();
    }

    // Get the user's full name from the session
    $fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : "";

    // Query the database for user records, including "Date of Birth"
    $query = "SELECT id, username, fullname, dob, gender, email, mobile, address, state, city FROM account";
    $statement = $pdo->query($query);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Display the HTML page
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Manage Users</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <p>Welcome <b><?php echo $fullName;?></b> | <a href="logout.php">Log out</a></p>
        <table>
            <tr class="title">
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>State</th>
                <th>City</th>
                <th>Delete</th>
            </tr>
            <?php
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['username']}</td>";
                echo "<td>{$user['fullname']}</td>";
                echo "<td>{$user['dob']}</td>";
                echo "<td>" . ($user['gender'] == 1 ? 'Male' : ($user['gender'] == 2 ? 'Female' : 'Other')) . "</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['mobile']}</td>";
                echo "<td>{$user['address']}</td>";
                echo "<td>{$user['state']}</td>";
                echo "<td>{$user['city']}</td>";
                echo "<td><a href='manage.php?id={$user['id']}' style='color: red;'>&#10060;</a></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div style="text-align: right;">
            <a href="index.php">Back</a>
        </div>
    </body>
    </html>

<?php
} else {
    echo "Sorry, but this account does not have permission to manage users";
    exit();
}
