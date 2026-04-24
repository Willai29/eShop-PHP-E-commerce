<?php
session_start();

include 'dbConfig.php';
include 'Cart.php';

$cart = new Cart();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in first!";
    header("Location: error.php");
    exit();
}

$first_name = $_SESSION['first_name'] ?? '';
$customerID = $_SESSION['sessCustomerID'] ?? 0;

$orders = $db->query("
    SELECT * 
    FROM orders 
    WHERE customer_id = '$customerID'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="dashboard">

    <aside class="sidebar">
        <div class="brand">E commerce</div>

        <a href="home.php">
            <i class="glyphicon glyphicon-th-large"></i> Product
        </a>

        <a href="viewCart.php">
            <i class="glyphicon glyphicon-shopping-cart"></i> Cart
        </a>

        <a href="myOrders.php" class="active">
            <i class="glyphicon glyphicon-list-alt"></i> My Orders
        </a>

        <a href="profile.php">
            <i class="glyphicon glyphicon-user"></i> Profile
        </a>

        <a href="logout.php">
            <i class="glyphicon glyphicon-log-out"></i> Logout
        </a>
    </aside>

    <main class="main">

        <div class="topbar">
            <h2 class="section-title">My Orders</h2>

            <div class="top-actions">
                <a href="viewCart.php" class="cart-pill">
                    Cart: <?= $cart->total_items(); ?>
                </a>

                <div class="user-pill">
                    <?= htmlspecialchars($first_name) ?>
                </div>
            </div>
        </div>

        <div class="cart-card">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Price</th>
                        <th>Date Ordered</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($orders && $orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($order['id']) ?></td>
                            <td>₱<?= htmlspecialchars($order['total_price']) ?></td>
                            <td><?= htmlspecialchars($order['created']) ?></td>
                            <td>
                                <span class="qty-badge">Ordered</span>
                            </td>
                            <td>
                                <a class="view-btn" href="orderDetails.php?id=<?= $order['id'] ?>">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-cart">
                            You have no orders yet.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

</div>
</body>
</html>