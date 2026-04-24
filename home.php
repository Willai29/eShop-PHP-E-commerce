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
    <title>Welcome <?= htmlspecialchars(trim($first_name . ' ' . $last_name)) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            background-color: #eeeeee;
        }

        .container {
            padding: 0;
        }

        .navbar {
            font-size: 17px;
            border-radius: 0;
        }

        .badge {
            font-size: 17px;
        }

        .search-box {
            margin-top: 15px;
        }

        .search-box input {
            width: 80%;
            height: 28px;
            color: #000;
            border-radius: 8px;
            border: 1px solid #333;
            padding: 5px 10px;
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 260px));
            gap: 24px;
            align-items: stretch;
        }

        .product-card {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            min-height: 430px;
            display: flex;
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 270px;
            object-fit: cover;
            display: block;
        }

        .no-image {
            width: 100%;
            height: 270px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
        }

        .product-card .caption {
            padding: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card h4 {
            margin: 0 0 5px 0;
            color: #000;
            text-align: left;
        }

        .product-card p {
            color: #000;
            text-align: left;
            margin: 3px 0;
        }

        .product-card .lead {
            font-size: 18px;
            margin-top: 8px;
        }

        .product-card .btn {
            margin-top: auto;
            width: fit-content;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">E-Shop</a>
        </div>

        <ul class="nav navbar-nav">
            <li class="active"><a href="home.php">Home</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="profile.php">
                    <span class="glyphicon glyphicon-user"></span>
                    <?= htmlspecialchars($first_name) ?>
                </a>
            </li>

            <li>
                <a href="logout.php">
                    <span class="glyphicon glyphicon-log-out"></span> Logout
                </a>
            </li>

            <li>
                <a href="viewCart.php">
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                    Cart:
                    <span class="badge"><?= $cart->total_items(); ?></span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <form action="" method="post" class="search-box">
        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Is it me you’re looking for?"
        >
        <button type="submit" style="border:none;">
            <i class="fa fa-search"></i>
        </button>
    </form>

    <br>
    <h3>Products</h3>
    <br>

    <?php if ($apiError !== ''): ?>
        <div class="alert alert-danger">
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

                    <div class="caption">
                        <h4><?= htmlspecialchars($row["name"] ?? '') ?></h4>

                        <p>Inventory product</p>

                        <p>
                            <strong>Quantity:</strong>
                            <?= htmlspecialchars((string)($row["quantity"] ?? '0')) ?>
                        </p>

                        <p class="lead">
                            Rs. <?= htmlspecialchars((string)($row["price"] ?? '0')) ?>
                        </p>

                        <?php if (($row["quantity"] ?? 0) > 0): ?>
                            <a class="btn btn-success"
                               href="cartAction.php?action=addToCart&id=<?= urlencode((string)($row["id"] ?? '')) ?>">
                                Add to cart
                            </a>
                        <?php else: ?>
                            <button class="btn btn-danger" disabled>Out of stock</button>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#000;">Product(s) not found.....</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>