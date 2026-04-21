<?php
session_start();

// initialize shopping cart class
include 'Cart.php';
$cart = new Cart;

// include database configuration file
include 'dbConfig.php';

if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){

    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = (int)$_REQUEST['id'];

        // get product details from API
        $apiUrl = "http://host.docker.internal:5000/api/products";
        $response = @file_get_contents($apiUrl);
        $products = $response ? json_decode($response, true) : [];

        $row = null;
        foreach($products as $product){
            if((int)$product['id'] === $productID){
                $row = $product;
                break;
            }
        }

        if($row){
            $itemData = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'qty' => 1
            );

            $insertItem = $cart->insert($itemData);
            $redirectLoc = $insertItem ? 'home.php' : 'index.php';
            header("Location: ".$redirectLoc);
            exit();
        }else{
            header("Location: home.php");
            exit();
        }

    }elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;

    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
        exit();

    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){
        // insert order details into MySQL database
        $insertOrder = $db->query("INSERT INTO orders (customer_id, total_price, created, modified) VALUES ('".$_SESSION['sessCustomerID']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");

        if($insertOrder){
            $orderID = $db->insert_id;
            $sql = '';

            // get cart items
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
            }

            // insert order items into MySQL
            $insertOrderItems = $db->multi_query($sql);

            if($insertOrderItems){
                $cart->destroy();
                header("Location: orderSuccess.php?id=$orderID");
                exit();
            }else{
                header("Location: checkout.php");
                exit();
            }
        }else{
            header("Location: checkout.php");
            exit();
        }

    }else if($_REQUEST['action'] == 'placeOrder1'){
        $orderID = $db->query("SELECT max(id) as maximum from orders");
        $row = mysqli_fetch_array($orderID);
        $r = $row['maximum'];
        header("Location: review.php?id=$r");
        exit();

    }else if($_REQUEST['action'] == 'placeOrder2'){
        $orderID = $_GET['id'];
        echo $orderID;
        exit();

    }else{
        header("Location: home.php");
        exit();
    }

}else{
    header("Location: home.php");
    exit();
}
?>