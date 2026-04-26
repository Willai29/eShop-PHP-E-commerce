<?php
session_start();

include 'Cart.php';
include 'dbConfig.php';

$cart = new Cart();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
    exit();
}

$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['last_name'] ?? '';
$email      = $_SESSION['email'] ?? '';
$address    = $_SESSION['address'] ?? '';
$phone      = $_SESSION['phone'] ?? '';

$customerID = $_SESSION['sessCustomerID'] ?? 0;

/* ===============================
   YOUR EXISTING QUERY (UNCHANGED)
================================ */
$orders = $db->query("
    SELECT 
        o.id AS order_id,
        oi.product_id,
        oi.quantity,
        o.total_price,
        o.created,
        'Ordered' AS status
    FROM orders o
    INNER JOIN order_items oi ON o.id = oi.order_id
    WHERE o.customer_id = '$customerID'
    ORDER BY o.id DESC
");

/* ===============================
   ✅ ADDED: GET PRODUCT NAMES FROM API
================================ */
$productNames = [];

$apiUrl = "http://host.docker.internal:5000/api/products";
$response = @file_get_contents($apiUrl);

if ($response) {
    $apiProducts = json_decode($response, true);

    if (is_array($apiProducts)) {
        foreach ($apiProducts as $p) {
            $productNames[$p['id']] = $p['name'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?= htmlspecialchars($first_name . ' ' . $last_name) ?></title>

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

        <a href="myOrders.php">
            <i class="glyphicon glyphicon-list-alt"></i> My Orders
        </a>

        <a href="profile.php" class="active">
            <i class="glyphicon glyphicon-user"></i> Profile
        </a>

        <a href="logout.php">
            <i class="glyphicon glyphicon-log-out"></i> Logout
        </a>
    </aside>

    <main class="main">

        <div class="topbar">
            <h2 class="section-title">My Profile</h2>

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

        <div class="profile-grid">

            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar">
                        <?= strtoupper(substr($first_name, 0, 1)) ?>
                    </div>
                    <div>
                        <h3><?= htmlspecialchars($first_name . ' ' . $last_name) ?></h3>
                        <p><?= htmlspecialchars($email) ?></p>
                    </div>
                </div>

                <form method="post" action="updateProfile.php" class="profile-form">
                    <div class="form-row">
                        <div>
                            <label>First Name</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars($first_name) ?>">
                        </div>

                        <div>
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars($last_name) ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div>
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
                        </div>
                    </div>

                    <div>
                        <label>Address</label>
                        <textarea name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
                    </div>

                    <button type="submit" class="save-profile-btn">
                        Save Changes
                    </button>
                </form>
            </div>

            <div class="profile-card">
                <h3 class="card-title">My Orders</h3>

                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($orders && $orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($order['order_id']) ?></td>

                                    <!-- ✅ UPDATED: SHOW PRODUCT NAME -->
                                    <td>
                                        <?= htmlspecialchars(
                                            $productNames[$order['product_id']] 
                                            ?? 'Product ID: ' . $order['product_id']
                                        ) ?>
                                    </td>

                                    <td><?= htmlspecialchars($order['quantity']) ?></td>

                                    <td>₱<?= htmlspecialchars($order['total_price']) ?></td>

                                    <td>
                                        <span style="background:#f0ebff;color:#6b4cff;padding:5px 10px;border-radius:15px;font-weight:700;">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-cart">
                                    No orders found yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </main>

</div>
</body>
</html>