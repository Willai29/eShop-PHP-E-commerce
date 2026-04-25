<?php
session_start();

include 'Cart.php';
$cart = new Cart;

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your cart!";
    header("location: error.php");
    exit();
}

$first_name = $_SESSION['first_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Cart</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="./js/jquery.min.js"></script>

    <script>
    function updateCartItem(obj,id){
        $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
            if(data == 'ok'){
                location.reload();
            }else{
                alert('Cart update failed, please try again.');
            }
        });
    }
    </script>
</head>

<body>
<div class="dashboard">

    <aside class="sidebar">
        <div class="brand">E commerce</div>

        <a href="home.php">
            <i class="fa fa-th-large"></i> Product
        </a>

        <a href="viewCart.php" class="active">
            <i class="fa fa-shopping-cart"></i> Cart
        </a>

        <a href="myOrders.php">
            <i class="fa fa-list-alt"></i> My Orders
        </a>

        <a href="profile.php">
            <i class="fa fa-user"></i> Profile
        </a>

        <a href="logout.php">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </aside>

    <main class="main">

        <div class="topbar">
            <h2 class="section-title">Shopping Cart</h2>

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

        <div class="cart-card">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if($cart->total_items() > 0): ?>
                        <?php $cartItems = $cart->contents(); ?>
                        <?php foreach($cartItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item["name"]); ?></td>
                                <td>₱<?= htmlspecialchars($item["price"]); ?></td>
                                <td>
                                    <input 
                                        type="number" 
                                        class="cart-qty" 
                                        value="<?= htmlspecialchars($item["qty"]); ?>" 
                                        min="1"
                                        onchange="updateCartItem(this, '<?= htmlspecialchars($item["rowid"]); ?>')"
                                    >
                                </td>
                                <td>₱<?= htmlspecialchars($item["subtotal"]); ?></td>
                                <td>
                                    <a 
                                        href="cartAction.php?action=removeCartItem&id=<?= htmlspecialchars($item["rowid"]); ?>" 
                                        class="remove-btn" 
                                        onclick="return confirm('Are you sure?')"
                                    >
                                        Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-cart">
                                Your cart is empty.....
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="cart-footer">
                <a href="home.php" class="continue-btn">
                    ← Continue Shopping
                </a>

                <?php if($cart->total_items() > 0): ?>
                    <div class="cart-total">
                        Total: ₱<?= htmlspecialchars($cart->total()); ?>
                    </div>

                    <a href="checkout.php" class="checkout-btn">
                        Checkout →
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </main>

</div>
</body>
</html>