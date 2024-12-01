<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Connect to the SQLite database
$db = new SQLite3('website.db');

// Retrieve the user's role from the database based on their username
$stmt = $db->prepare('SELECT role, avatar FROM users WHERE username = :username');
$stmt->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
$result = $stmt->execute();

$userData = $result->fetchArray(SQLITE3_ASSOC);

if (!$userData) {
    // If the user doesn't exist in the database, force logout
    header('Location: logout.php');
    exit;
}

$role = $userData['role']; // Fetch the role from the database
$avatar = $userData['avatar'];
$isAdmin = ($role === 'admin'); // Check if the user is an admin
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Our Site</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="index.php" class="back-button">Back to Home</a>
        <a href="logout.php">Logout</a>
    </header>
    
    <main>
        <h2>Your Profile</h2>
        <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        
        <p>Your avatar:</p>
        <img src="uploads/<?php echo htmlspecialchars($avatar); ?>" alt="Your Avatar">

        <!-- If the user is an admin, show the admin dashboard -->
        <?php if ($isAdmin): ?>
        <section>
            <h3>Admin Dashboard</h3>
            <h4>All Registered Users</h4>
            <ul>
                <?php
                // Query to get all users and their roles
                $result = $db->query('SELECT username, role FROM users');
                
                // Loop through the results and display all users
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<li>";
                    echo "Username: " . htmlspecialchars($row['username']);
                    echo " | Role: " . htmlspecialchars($row['role']);
                    if ($row['role'] !== 'admin') {
                        $newRole = ($row['role'] === 'user') ? 'disabled' : 'user';
                        echo " | <a href='?action=toggle_role&username=" . urlencode($row['username']) . "&newRole=" . urlencode($newRole) . "'>Toggle Role</a>";
                    }
                                    
                     
                    echo "</li>";
                }
                
                // Handle the role toggling action
                if (isset($_GET['action']) && $_GET['action'] === 'toggle_role') {
                $usernameToToggle = $_GET['username'];
                $newRole = $_GET['newRole'];

                // Update the user's role
                $stmt = $db->prepare('UPDATE users SET role = :newRole WHERE username = :username');
                $stmt->bindValue(':newRole', $newRole, SQLITE3_TEXT);
                $stmt->bindValue(':username', $usernameToToggle, SQLITE3_TEXT);
                $stmt->execute();

                header('Location: profile.php');
                exit;
                }
                ?>
            </ul>
        </section>
        <?php endif; ?>
    </main>
</body>
</html>
