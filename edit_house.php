<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_agent']) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Fetch current data for the selected house
$stmt = $pdo->prepare("SELECT * FROM houses WHERE id = ? AND seller_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$house = $stmt->fetch();

if (!$house) {
    echo "House not found or you're not authorized.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $contact_number = $_POST['contact_number'];

   // Update query using PDO
   try {
        // Prepare an update statement
        $stmt_update = $pdo->prepare("UPDATE houses SET title=?, description=?, price=?, bedrooms=?, bathrooms=?, area=?, address=?, city=?, state=?, zip_code=? WHERE id=?");

        // Bind variables to the prepared statement as parameters
        $stmt_update->execute([
            htmlspecialchars(strip_tags(trim($title))),
            htmlspecialchars(strip_tags(trim($description))),
            floatval(htmlspecialchars(strip_tags(trim($price)))),
            intval(htmlspecialchars(strip_tags(trim($bedrooms)))),
            intval(htmlspecialchars(strip_tags(trim($bathrooms)))),
            floatval(htmlspecialchars(strip_tags(trim($area)))),
            htmlspecialchars(strip_tags(trim($address))),
            htmlspecialchars(strip_tags(trim($city))),
            htmlspecialchars(strip_tags(trim($state))),
            htmlspecialchars(strip_tags(trim($zip_code))), 
            intval($_GET["id"])
        ]);

        echo "Records updated successfully.";

    } catch(Exception $e){
        die('Error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit House</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            background-color: #fff;
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
        a:hover {
            color: #4CAF50;
        }
    </style>
<body>
    <h2>Edit House Listing</h2>
    <form method="post">
        <input type="text" name="title" value="<?php echo htmlspecialchars($house['title']); ?>" required><br>
        <textarea name="description" required><?php echo htmlspecialchars($house['description']); ?></textarea><br>
        <input type="number" name="price" value="<?php echo number_format($house['price'], 2); ?>" step="0.01" required><br>
        <input type="number" name="bedrooms" value="<?php echo $house['bedrooms']; ?>" required><br>
        <input type="number" name="bathrooms" value="<?php echo htmlspecialchars($house['bathrooms']); ?>" required><br>
        <input type="number" name="area" value="<?php echo htmlspecialchars($house['area']); ?>" step="0.01" required><br>
        <input type="text" name="address" value="<?php echo htmlspecialchars($house['address']); ?>" required><br>
        <input type="text" name="city" value="<?php echo htmlspecialchars($house['city']); ?>" required><br>
        <input type="text" name="state" value="<?php echo htmlspecialchars($house['state']); ?>" required><br>
        <input type="text" name="zip_code" value="<?php echo htmlspecialchars($house['zip_code']); ?>" required><br>
        <input type="tel" name="contact_number" placeholder="Contact Number" value="<?php echo htmlspecialchars($house['contact_number']); ?>" required><br>required>
        <input type="submit" value="Update House">
    </form>
    <a href="index.php">Back to Listings</a>
</body>
</html>