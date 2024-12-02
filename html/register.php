<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $db = new SQLite3('website.db');

    // Sanitize input
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    
    // Secure avatar upload
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file types
    $max_file_size = 2 * 1024 * 1024; // Max size: 2MB
    $upload_dir = 'uploads/';
    $avatar = 'default.jpg'; // Default avatar if no file is uploaded

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_name = basename($_FILES['avatar']['name']);
        $file_size = $_FILES['avatar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check file size
        if ($file_size > $max_file_size) {
            echo '<div class="message error">File size exceeds the maximum limit of 2MB.</div>';
            exit;
        }

        // Check file extension
        if (!in_array($file_ext, $allowed_extensions)) {
            echo '<div class="message error">Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.</div>';
            exit;
        }

        // Check if the file is a valid image
        if (!getimagesize($file_tmp)) {
            echo '<div class="message error">The uploaded file is not a valid image.</div>';
            exit;
        }

        // Sanitize the file name
        $safe_file_name = preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($file_name, PATHINFO_FILENAME)) . '.' . $file_ext;

        // Generate unique file name to prevent overwriting
        $unique_file_name = uniqid('avatar_', true) . '.' . $file_ext;

        // Move the file to the uploads directory
        if (move_uploaded_file($file_tmp, $upload_dir . $unique_file_name)) {
            $avatar = $unique_file_name;
        } else {
            echo '<div class="message error">Failed to upload the file.</div>';
            exit;
        }
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
