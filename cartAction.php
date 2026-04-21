<?php
<<<<<<< HEAD
session_start();

=======
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
// initialize shopping cart class
include 'Cart.php';
$cart = new Cart;

// include database configuration file
include 'dbConfig.php';
<<<<<<< HEAD

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

=======
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = $_REQUEST['id'];
        // get product details
        $query = $db->query("SELECT * FROM products WHERE id = ".$productID);
        $row = $query->fetch_assoc();
        $itemData = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => 1
        );

        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem?'home.php':'index.php';
        header("Location: ".$redirectLoc);
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
    }elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
<<<<<<< HEAD
        echo $updateItem ? 'ok' : 'err';
        die;

    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
        exit();

    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){
        // insert order details into MySQL database
=======
        echo $updateItem?'ok':'err';die;
    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){
        // insert order details into database
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
        $insertOrder = $db->query("INSERT INTO orders (customer_id, total_price, created, modified) VALUES ('".$_SESSION['sessCustomerID']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");

        if($insertOrder){
            $orderID = $db->insert_id;
            $sql = '';
<<<<<<< HEAD

=======
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
            // get cart items
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
            }
<<<<<<< HEAD

            // insert order items into MySQL
=======
            // insert order items into database
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
            $insertOrderItems = $db->multi_query($sql);

            if($insertOrderItems){
                $cart->destroy();
                header("Location: orderSuccess.php?id=$orderID");
<<<<<<< HEAD
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
=======
            }else{
                header("Location: checkout.php");
            }
        }else{
            header("Location: checkout.php");
        }
    }else if($_REQUEST['action'] == 'placeOrder1'){
        $orderID = $db->query("SELECT max(id) as maximum from orders");
        $row = mysqli_fetch_array($orderID);
        $r=$row['maximum'];
        header("Location: review.php?id=$r");
    }else if($_REQUEST['action']== 'placeOrder2'){
        session_start();
        $orderID=$_GET['id'];
        echo $orderID;
    }
    else{
        header("Location: home.php");
    }
}else{
    header("Location: home.php");
}
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
