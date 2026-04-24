<?php
session_start();
include 'dbConfig.php';
include 'Cart.php';

$cart = new Cart();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("Location: index.php");
    exit();
}

$orderID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$first_name = $_SESSION['first_name'] ?? '';

$order = $db->query("SELECT * FROM orders WHERE id = '$orderID'")->fetch_assoc();

$items = $db->query("
    SELECT oi.*, p.name, p.price
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = '$orderID'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="brand">E commerce</div>
        <a href="home.php">Product</a>
        <a href="viewCart.php">Cart</a>
        <a href="myOrders.php" class="active">My Orders</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </aside>

    <main class="main">
        <div class="topbar">
            <h2 class="section-title">Order Details</h2>
            <div class="user-pill"><?= htmlspecialchars($first_name) ?></div>
        </div>

        <div class="cart-card">
            <h3>Order ID: #<?= htmlspecialchars($orderID) ?></h3>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items && $items->num_rows > 0): ?>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_id']) ?></td>
                                <td><?= htmlspecialchars($item['name'] ?? 'Inventory Product') ?></td>
                                <td>₱<?= htmlspecialchars($item['price'] ?? '0') ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td>₱<?= htmlspecialchars(($item['price'] ?? 0) * $item['quantity']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-cart">No order items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br>
            <a href="myOrders.php" class="continue-btn">← Back to My Orders</a>
        </div>
    </main>
</div>
</body>
</html>