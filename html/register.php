<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $db = new SQLite3('website.db');

    // Sanitize input
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    
    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = $_FILES['avatar']['name'];
        move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/$avatar");
    } else { 
        $avatar = 'default.jpg'; // Default avatar if no file is uploaded
    }

    // Prepare the SQL statement to insert the new user
    $stmt = $db->prepare('INSERT INTO users (username, password, role, avatar) VALUES (:username, :password, :role, :avatar)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':role', 'user', SQLITE3_TEXT); // Default role is 'user'
    $stmt->bindValue(':avatar', $avatar, SQLITE3_TEXT);

    // Execute the statement and handle the result
    try {
        if ($stmt->execute()) {
            echo '<div class="message success">
                    Registration successful! <a href="login.php">Login here</a>
                  </div>';
        } else {
            echo '<div class="message error">
                    Error: Unable to register. Username may already exist.
                  </div>';
        }
    } catch (Exception $e) {
        echo '<div class="message error">An error occurred: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Our Site</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>
    
    <main>
        <form method="post" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label>Avatar</label>
            <input type="file" name="avatar">
            <button type="submit">Register</button>
        </form>
    </main>
</body>
</html>
