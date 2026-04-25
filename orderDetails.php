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

// Get order items
$items = $db->query("
    SELECT product_id, quantity
    FROM order_items
    WHERE order_id = '$orderID'
");

// Fetch API products
$apiProducts = [];
$apiUrl = "http://host.docker.internal:5000/api/products";
$response = @file_get_contents($apiUrl);

if ($response) {
    $apiProducts = json_decode($response, true);
}

// Function to match product
function findProductFromApi($products, $productID) {
    foreach ($products as $p) {
        if ((int)$p['id'] === (int)$productID) {
            return $p;
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
            padding: 30px;
            gap: 28px;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #5026ff, #8064ff);
            color: #fff;
            border-radius: 28px;
            padding: 30px 22px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px;
            border-radius: 12px;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .sidebar a.active {
            background: #fff;
            color: #5026ff;
        }

        .main {
            flex: 1;
            background: #fff;
            border-radius: 25px;
            padding: 25px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .user-pill {
            background: #eee;
            padding: 8px 14px;
            border-radius: 15px;
            font-weight: bold;
        }

        .cart-card {
            background: #f9f9ff;
            border-radius: 20px;
            padding: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table thead tr {
            display: table;
            width: 100%;
        }

        .cart-table tbody tr {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            background: #f0f1f7;
            border-radius: 10px;
        }

        .cart-table th {
            text-align: left;
            padding: 14px;
            font-size: 13px;
            color: #777;
        }

        .cart-table td {
            padding: 16px;
            font-weight: 600;
        }

        /* column widths */
        .cart-table th:nth-child(1),
        .cart-table td:nth-child(1) { width: 15%; }

        .cart-table th:nth-child(2),
        .cart-table td:nth-child(2) { width: 30%; }

        .cart-table th:nth-child(3),
        .cart-table td:nth-child(3) { width: 15%; }

        .cart-table th:nth-child(4),
        .cart-table td:nth-child(4) { width: 15%; }

        .cart-table th:nth-child(5),
        .cart-table td:nth-child(5) { width: 15%; }

        /* center numbers */
        .cart-table td:nth-child(1),
        .cart-table td:nth-child(3),
        .cart-table td:nth-child(4),
        .cart-table td:nth-child(5) {
            text-align: center;
        }

        .cart-table tbody tr:hover {
            background: #e9e6ff;
        }

        .continue-btn {
            display: inline-block;
            margin-top: 15px;
            background: #7b61ff;
            color: #fff;
            padding: 10px 16px;
            border-radius: 20px;
            text-decoration: none;
        }

        .continue-btn:hover {
            background: #5e49d8;
        }
    </style>
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
            <h2>Order Details</h2>
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

                            <?php
                                $product = findProductFromApi($apiProducts, $item['product_id']);

                                $name = $product['name'] ?? 'Inventory Product';
                                $price = $product['price'] ?? 0;
                                $subtotal = $price * $item['quantity'];
                            ?>

                            <tr>
                                <td><?= $item['product_id'] ?></td>
                                <td><?= htmlspecialchars($name) ?></td>
                                <td>₱<?= $price ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₱<?= $subtotal ?></td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No order items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="myOrders.php" class="continue-btn">← Back to My Orders</a>

        </div>

    </main>

</div>

</body>
</html>