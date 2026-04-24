<?php
ob_start();
session_start();

include 'dbConfig.php';
include 'Cart.php';

$cart = new Cart();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your profile!";
    header("Location: error.php");
    exit();
}

$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['last_name'] ?? '';

$products = [];
$apiError = '';

$apiUrl = "http://host.docker.internal:5000/api/products";

if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false) {
        $apiError = "API request failed: " . $curlError;
    } else {
        $decoded = json_decode($response, true);

        if ($httpCode !== 200) {
            $apiError = "API returned HTTP status " . $httpCode;
        } elseif (!is_array($decoded)) {
            $apiError = "Invalid API response.";
        } else {
            $products = $decoded;
        }
    }
} else {
    $apiError = "PHP cURL extension is not enabled.";
}

$search = trim($_POST['search'] ?? '');

if ($search !== '') {
    $products = array_filter($products, function ($p) use ($search) {
        return isset($p['name']) && stripos($p['name'], $search) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>E-Shop Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            color: #222;
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
            box-shadow: 0 20px 40px rgba(80, 38, 255, 0.25);
            position: sticky;
            top: 30px;
            height: calc(100vh - 60px);
        }

        .brand {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 38px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #eee;
            padding: 13px 14px;
            margin-bottom: 10px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #ffffff;
            color: #5026ff;
        }

        .main {
            flex: 1;
            background: rgba(255,255,255,0.65);
            border-radius: 30px;
            padding: 28px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.08);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            width: 420px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        .search-form input {
            border: none;
            outline: none;
            padding: 13px 16px;
            flex: 1;
            color: #222;
            background: #fff;
        }

        .search-form button {
            width: 58px;
            border: none;
            background: #7b61ff;
            color: #fff;
            font-size: 18px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .cart-pill {
            background: #fff;
            color: #222;
            padding: 10px 16px;
            border-radius: 16px;
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            font-weight: 600;
        }

        .user-pill {
            background: #fff;
            padding: 10px 16px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            font-weight: 600;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0 24px;
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 35px rgba(0,0,0,0.14);
        }

        .product-image {
            width: 100%;
            height: 210px;
            object-fit: cover;
            background: #f2f2f2;
        }

        .no-image {
            height: 210px;
            background: #e9e9e9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-weight: 600;
        }

        .product-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-info h4 {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .product-desc {
            color: #777;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .qty-badge {
            display: inline-block;
            width: fit-content;
            background: #f0ebff;
            color: #6b4cff;
            padding: 5px 11px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            gap: 10px;
        }

        .price {
            font-size: 19px;
            font-weight: 800;
            color: #222;
        }

        .buy-btn {
            background: #7b61ff;
            color: #fff;
            border-radius: 18px;
            padding: 8px 14px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: 0.2s;
        }

        .buy-btn:hover {
            background: #6248e8;
            color: #fff;
            text-decoration: none;
        }

        .out-btn {
            background: #ddd;
            color: #777;
            border-radius: 18px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            border: none;
        }

        .alert-box {
            background: #ffecec;
            color: #c0392b;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .empty-message {
            background: #fff;
            padding: 24px;
            border-radius: 18px;
            color: #777;
        }

        @media (max-width: 900px) {
            .dashboard {
                flex-direction: column;
                padding: 15px;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <aside class="sidebar">
        <div class="brand">E commerce</div>

        <a href="home.php" class="active">
            <i class="fa fa-th-large"></i> Product
        </a>

        <a href="viewCart.php">
            <i class="fa fa-shopping-cart"></i> Cart
        </a>

        <a href="profile.php">
            <i class="fa fa-user"></i> Profile
        </a>

        <a href="myOrders.php">
            <i class="glyphicon glyphicon-list-alt"></i> My Orders
        </a>

        <a href="logout.php">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </aside>

    <main class="main">

        <div class="topbar">
            <form action="" method="post" class="search-form">
                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Search product..."
                >
                <button type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <div class="top-actions">
                <a href="viewCart.php" class="cart-pill">
                    <i class="fa fa-shopping-cart"></i>
                    Cart: <?= $cart->total_items(); ?>
                </a>

                <div class="user-pill">
                    <i class="fa fa-user-circle"></i>
                    <?= htmlspecialchars($first_name) ?>
                </div>
            </div>
        </div>

        <h2 class="section-title">Product</h2>

        <?php if ($apiError !== ''): ?>
            <div class="alert-box">
                <?= htmlspecialchars($apiError) ?>
            </div>
        <?php endif; ?>

        <div id="products" class="products-container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $row): ?>

                    <?php
                    $imageSrc = '';

                    if (!empty($row["image_url"])) {
                        $imageSrc = $row["image_url"];
                    } elseif (!empty($row["image"])) {
                        $imageSrc = "data:image/jpeg;base64," . $row["image"];
                    }

                    $qty = (int)($row["quantity"] ?? 0);
                    ?>

                    <div class="product-card">
                        <?php if (!empty($imageSrc)): ?>
                            <img
                                class="product-image"
                                src="<?= htmlspecialchars($imageSrc) ?>"
                                alt="<?= htmlspecialchars($row["name"] ?? '') ?>"
                            >
                        <?php else: ?>
                            <div class="no-image">No Image</div>
                        <?php endif; ?>

                        <div class="product-info">
                            <h4><?= htmlspecialchars($row["name"] ?? '') ?></h4>

                            <div class="product-desc">Inventory product</div>

                            <span class="qty-badge">
                                Quantity: <?= htmlspecialchars((string)$qty) ?>
                            </span>

                            <div class="price-row">
                                <div class="price">
                                    ₱<?= htmlspecialchars((string)($row["price"] ?? '0')) ?>
                                </div>

                                <?php if ($qty > 0): ?>
                                    <a href="#"
                                       class="buy-btn add-to-cart-btn"
                                       data-id="<?= urlencode((string)($row["id"] ?? '')) ?>">
                                        Buy now
                                    </a>
                                <?php else: ?>
                                    <button class="out-btn" disabled>Out</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-message">Product(s) not found.....</div>
            <?php endif; ?>
        </div>

    </main>

</div>

<script>
document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        let productId = this.getAttribute('data-id');

        fetch('cartAction.php?action=addToCart&id=' + productId + '&ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Product has been added to cart!');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to add product to cart.');
                }
            })
            .catch(() => {
                alert('Something went wrong.');
            });
    });
});
</script>

</body>
</html>