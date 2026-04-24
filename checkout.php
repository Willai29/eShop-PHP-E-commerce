<?php
session_start();

include 'dbConfig.php';
include 'Cart.php';

$cart = new Cart;

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your cart!";
    header("location: error.php");
    exit();
}

if ($cart->total_items() <= 0) {
    header("Location: home.php");
    exit();
}

$query = $db->query("SELECT * FROM customers WHERE id = " . $_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();

$first_name = $_SESSION['first_name'] ?? $custRow['first_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

        <a href="profile.php">
            <i class="glyphicon glyphicon-user"></i> Profile
        </a>

        <a href="logout.php">
            <i class="glyphicon glyphicon-log-out"></i> Logout
        </a>
    </aside>

    <main class="main">

        <div class="topbar">
            <h2 class="section-title">Checkout</h2>

            <div class="top-actions">
                <a href="viewCart.php" class="cart-pill">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                    Cart: <?= $cart->total_items(); ?>
                </a>

                <div class="user-pill">
                    <i class="glyphicon glyphicon-user"></i>
                    <?= htmlspecialchars($first_name) ?>
                </div>
            </div>
        </div>

        <div class="checkout-grid">

            <div class="checkout-card">
                <h3 class="checkout-title">Order Preview</h3>

                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($cart->total_items() > 0): ?>
                        <?php $cartItems = $cart->contents(); ?>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item["name"]); ?></td>
                                <td>₱<?= htmlspecialchars($item["price"]); ?></td>
                                <td><?= htmlspecialchars($item["qty"]); ?></td>
                                <td>₱<?= htmlspecialchars($item["subtotal"]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-cart">No items in your cart......</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <div class="checkout-total">
                    <span>Total</span>
                    <strong>₱<?= htmlspecialchars($cart->total()); ?></strong>
                </div>
            </div>

            <div class="shipping-card">
                <h3 class="checkout-title">Shipping Details</h3>

                <div class="shipping-info">
                    <div>
                        <span>Name</span>
                        <strong><?= htmlspecialchars($custRow['first_name'] . ' ' . $custRow['last_name']); ?></strong>
                    </div>

                    <div>
                        <span>Email</span>
                        <strong><?= htmlspecialchars($custRow['email']); ?></strong>
                    </div>

                    <div>
                        <span>Phone</span>
                        <strong><?= htmlspecialchars($custRow['phone']); ?></strong>
                    </div>

                    <div>
                        <span>Address</span>
                        <strong><?= htmlspecialchars($custRow['address']); ?></strong>
                    </div>
                </div>

                <a href="cartAction.php?action=placeOrder" class="place-order-btn">
                    Place Order →
                </a>
            </div>

        </div>

        <div class="checkout-actions">
            <a href="home.php" class="continue-btn">
                ← Continue Shopping
            </a>
        </div>

    </main>

</div>
</body>
</html>