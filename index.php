<?php
session_start();
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM houses ORDER BY created_at DESC");
$houses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Market</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    header {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
        
        
    }
    header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://media4.giphy.com/media/e8ik35i8LaO3BqRwY6/giphy.gif') center/cover no-repeat;
        opacity: 0.3;
        z-index: 1;
    }
    header h1 {
        margin: 0 0 1rem;
        position: relative;
        z-index: 2;
    }
    nav {
        position: relative;
        z-index: 2;
    }
    .btn {
        display: inline-block;
        background: #4CAF50;
        color: #fff;
        padding: 0.7rem 1.2rem;
        text-decoration: none;
        border-radius: 5px;
        margin: 0.3rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
        background: #45a049;
        transform: translateY(-2px);
    }
    main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    .message {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 5px;
        text-align: center;
    }
    .success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    .error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    .house-listings {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .house-card {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .house-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .house-card h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        color: #333;
    }
    .house-actions {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .house-actions .btn {
        flex: 1;
        text-align: center;
        margin: 0.3rem;
    }
    .btn-danger {
        background: #dc3545;
    }
    .btn-danger:hover {
        background: #c82333;
    }
    footer {
        text-align: center;
        padding: 1.5rem;
        background: #333;
        color: #fff;
        margin-top: 2rem;
    }
</style>
</head>
<body>
    <header>
        <h1>Welcome to House Market</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, User #<?php echo $_SESSION['user_id']; ?></p>
                <?php if ($_SESSION['is_agent']): ?>
                    <a href="list_house.php" class="btn">List a House</a>
                <?php endif; ?>
                <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='message success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='message error'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <h2>Latest Listings</h2>
        <div class="house-listings">
            <?php foreach ($houses as $house): ?>
                <div class="house-card">
                    <h3><?php echo htmlspecialchars($house['title']); ?></h3>
                    <p>Price: $<?php echo number_format($house['price'], 2); ?></p>
                    <p><?php echo $house['bedrooms']; ?> bed, <?php echo $house['bathrooms']; ?> bath</p>
                    <p><?php echo htmlspecialchars($house['city']); ?>, <?php echo htmlspecialchars($house['state']); ?></p>
                    <div class="house-actions">
                        <a href="view_house.php?id=<?php echo $house['id']; ?>" class="btn">View Details</a>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['is_agent'] && $house['seller_id'] == $_SESSION['user_id']): ?>
                            <a href="edit_house.php?id=<?php echo $house['id']; ?>" class="btn">Edit</a>
                            <a href="delete_house.php?id=<?php echo $house['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this listing?');">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024  Hritik. All rights reserved.</p>
    </footer>
</body>
</html>