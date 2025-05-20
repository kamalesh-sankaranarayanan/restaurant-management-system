<?php
session_start();
include 'db.php';

// Fetch all menu items
$query = "SELECT * FROM items";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu - XYZ Restaurant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8f0;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 20px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #ffcc66;
        }

        form {
            display: inline-block;
        }

        input[type="number"] {
            width: 60px;
        }

        button {
            background-color: #ff9933;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e68a00;
        }

        .nav {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #ff9933;
            font-weight: bold;
        }

        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="cart.php">View Cart</a>
    <a href="logout.php">Logout</a>
</div>

<h2>Our Menu</h2>

<table>
    <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form action="add_to_cart.php" method="POST">
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
                    <input type="number" name="quantity" value="1" min="1" required>
                </td>
                <td><button type="submit">Add to Cart</button></td>
            </form>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
