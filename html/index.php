<?php
session_start();
include 'logger.php';
$logger = new Logger();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Connect to the SQLite database
    $db = new SQLite3('website.db');
    // Query to fetch the user's role
    $stmt = $db->prepare('SELECT role FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    $role = $user['role'] ?? null;

    // If the user's role is disabled, restrict access
    if ($role === 'disabled') {
        $restricted = true;
    } else {
        $restricted = false;
    }
} else {
    $restricted = true; // User is not logged in
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Site</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Our Site!</h1>
        <a href="index.php" class="back-button">Back to Home</a>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
            <a href="profile.php">View Profile</a> | <a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
        <?php endif; ?>
    </header>
    
    <main>
        <h2>Featured Content</h2>
        <p>Explore some interesting features below!</p>

        <?php if (!$restricted): ?>
            <!-- Search Feature -->
            <section> 
                <h3>Search Products</h3>
                <form method="GET" action="index.php">
                    <label for="search">Enter Keywords:</label>
                    <input type="text" name="search" id="search" placeholder="Enter search terms" />
                    <button type="submit">Search</button>
                </form>
                <?php   
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    if (strpos($search, '-') !== false) {
                        $logger->log($username, "SQLI", $search);
                    }

                    echo "<h4>Search Results for '" . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . "':</h4>";
        
                    $db = new SQLite3('database.db');
            
                    $query = "SELECT * FROM products WHERE product_name LIKE '%$search%' OR category LIKE '%$search%'";
                    $result = @$db->query($query);

            
                    echo '<ul>';
                    if ($result) {
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            echo "<li>";
                            echo "<strong>Product Name:</strong> " . htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') . "<br>";
                            echo "<strong>Description:</strong> " . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "<br>";
                            echo "<strong>Category:</strong> " . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8');
                            echo "</li>";
                        }
                    } else {
                        echo '<li>No results found or invalid query.</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </section>

            <!-- User Feedback -->
            <section>
              <h3>Share Your Feedback</h3>
                <form method="POST" action="index.php">
                    <label for="comment">Your Feedback:</label>
                    <input type="text" name="comment" id="comment" placeholder="Share your thoughts" maxlength="255" />
                    <button type="submit">Submit</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
                    $comment = $_POST['comment'];
                    if(strpos($comment,'<')!==false){
                        $logger->log($username,"XSS", $comment);
                    }  
                    echo "<h4>Your feedback:</h4>";
            
                    echo "<div title='$comment' onmouseover='alert(\"$comment\")'>Hover over this box to see your feedback</div>";

            
                }
                ?>
            </section>

            <section>
                <h3>Select Language Article</h3>
                <form method="GET" action="index.php">
                    <label for="lang">Choose Language:</label>
                    <select name="lang" id="lang">
                        <option value="english">English</option>
                        <option value="german">German</option>
                    </select>
                    <button type="submit">View Article</button>
                </form>

                <?php
                if (isset($_GET['lang'])) {
                    $language = $_GET['lang'];

                    // Whitelist of allowed languages
                    $allowedLanguages = ['english', 'german'];

                    // We only allow files from the 'languages' directory
                    if (in_array($language, $allowedLanguages)) {
                        $file = __DIR__ . "/languages/" . $language . ".php";
                        
                        if (file_exists($file)) {
                            include($file);
                        } else {
                            echo '<p>File not found.</p>';
                        }
                    } 
                    else{
                        $file = __DIR__ . "/languages/" . $language . ".php";
                         
                        if (file_exists($file)) {
                            include($file);
                            $logger->log($username,"LFI", "/languages/". $language);
                        } else {
                            $logger->log($username,"LFI","/languages/". $language);
                            echo '<p>File not found.</p>';
                        }
                    }
                }
                ?>
            </section>

        <?php else: ?>
            <p>You are not logged in, or your account has been suspended.<br>
             Please log in to access these features!</p>
        <?php endif; ?>
    </main>
</body>
</html>