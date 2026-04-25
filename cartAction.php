<?php
session_start();

include 'Cart.php';
$cart = new Cart;

include 'dbConfig.php';

function respondAjax($status, $message) {
    if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $status,
            "message" => $message
        ]);
        exit();
    }
}

function getInventoryProductById($productID) {
    $apiUrl = "http://host.docker.internal:5000/api/products";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) return null;

    $products = json_decode($response, true);
    if (!is_array($products)) return null;

    foreach ($products as $product) {
        if (isset($product['id']) && (int)$product['id'] === (int)$productID) {
            return $product;
        }
    }

    return null;
}

function deductInventoryQuantity($productID, $qty = 1) {
    $deductUrl = "http://host.docker.internal:5000/api/products/" . $productID . "/deduct";

    $ch = curl_init($deductUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["qty" => $qty]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode === 200;
}

function sendOrderToInventory($orderID, $customerID, $total, $cartItems) {
    $url = "http://host.docker.internal:5000/api/order";

    $payload = [
        "order_id" => $orderID,
        "customer_id" => $customerID,
        "total" => $total,
        "items" => []
    ];

    foreach ($cartItems as $item) {
        $payload["items"][] = [
            "product_id" => $item["id"],
            "name" => $item["name"],
            "qty" => $item["qty"],
            "price" => $item["price"]
        ];
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $httpCode === 200;
}

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {

    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {

        $productID = (int)$_REQUEST['id'];
        $row = getInventoryProductById($productID);

        if ($row === null || (int)$row['quantity'] <= 0) {
            respondAjax("error", "Product is out of stock.");
            header("Location: home.php?error=out_of_stock");
            exit();
        }

        $deductSuccess = deductInventoryQuantity($productID, 1);

        if (!$deductSuccess) {
            respondAjax("error", "Failed to deduct inventory quantity.");
            header("Location: home.php?error=deduct_failed");
            exit();
        }

        $itemData = array(
            'id'    => $row['id'] ?? $productID,
            'name'  => $row['name'] ?? 'Unknown Product',
            'price' => $row['price'] ?? 0,
            'qty'   => 1
        );

        $insertItem = $cart->insert($itemData);

        if ($insertItem) {
            respondAjax("success", "Product has been added to cart.");
        } else {
            respondAjax("error", "Failed to add product to cart.");
        }

        header("Location: " . ($insertItem ? "viewCart.php" : "home.php"));
        exit();

    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])) {

        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty'   => $_REQUEST['qty']
        );

        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        exit();

    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {

        $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
        exit();

    } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])) {

        $customerID = $_SESSION['sessCustomerID'];
        $total = $cart->total();
        $created = date("Y-m-d H:i:s");

        $insertOrder = $db->query("
            INSERT INTO orders (customer_id, total_price, created, modified)
            VALUES ('$customerID', '$total', '$created', '$created')
        ");

        if ($insertOrder) {
            $orderID = $db->insert_id;
            $cartItems = $cart->contents();

            foreach ($cartItems as $item) {
                $productID = $item['id'];
                $qty = $item['qty'];

                $db->query("
                    INSERT INTO order_items (order_id, product_id, quantity)
                    VALUES ('$orderID', '$productID', '$qty')
                ");
            }

            sendOrderToInventory($orderID, $customerID, $total, $cartItems);

            $cart->destroy();

            header("Location: orderSuccess.php?id=$orderID");
            exit();

        } else {
            header("Location: checkout.php");
            exit();
        }

    } else {
        header("Location: home.php");
        exit();
    }

} else {
    header("Location: home.php");
    exit();
}
?>