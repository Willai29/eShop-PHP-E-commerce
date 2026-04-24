<?php
session_start();

include 'Cart.php';
$cart = new Cart;

include 'dbConfig.php';

function getInventoryProductById($productID) {
    $apiUrl = "http://host.docker.internal:5000/api/products";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return null;
    }

    $products = json_decode($response, true);

    if (!is_array($products)) {
        return null;
    }

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

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $httpCode === 200;
}

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {

    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {

        $productID = (int)$_REQUEST['id'];
        $row = getInventoryProductById($productID);

        if ($row === null) {
            header("Location: home.php");
            exit();
        }

        if ((int)$row['quantity'] <= 0) {
            header("Location: home.php");
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
            deductInventoryQuantity($productID, 1);
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

    } else {
        header("Location: home.php");
        exit();
    }

} else {
    header("Location: home.php");
    exit();
}
?>