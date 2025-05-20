<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $table_id = $_POST['table_id'] ?? '';
    $order_date = date('Y-m-d H:i:s');
    $total_amount = $_POST['total_amount'] ?? 0;

    $item_ids = $_POST['item_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $item_prices = $_POST['item_price'] ?? [];

    if (empty($username) || empty($table_id) || empty($item_ids)) {
        echo "Invalid request data.";
        exit();
    }

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (username, table_id, order_date, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisd", $username, $table_id, $order_date, $total_amount);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert into orderdetails table
    $stmt_detail = $conn->prepare("INSERT INTO orderdetails (order_id, item_id, quantity, item_price) VALUES (?, ?, ?, ?)");
    for ($i = 0; $i < count($item_ids); $i++) {
        $stmt_detail->bind_param("iiid", $order_id, $item_ids[$i], $quantities[$i], $item_prices[$i]);
        $stmt_detail->execute();
    }

    // Mark the table as occupied
    $update = $conn->prepare("UPDATE tables SET is_available = 0 WHERE table_id = ?");
    $update->bind_param("i", $table_id);
    $update->execute();

    // Clear the cart
    unset($_SESSION['cart']);
    $_SESSION['table_id'] = $table_id;

    // Redirect to payment success
    header("Location: payment_success.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
