<?php
session_start();
require 'db.php';

$orderID = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Success</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        .success-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .success-card {
            width: 520px;
            background: #fff;
            border-radius: 30px;
            padding: 45px 38px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }

        .success-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            border-radius: 28px;
            background: linear-gradient(180deg, #4b2cff, #7a5cff);
            color: #fff;
            font-size: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-card h1 {
            color: #222;
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .success-card p {
            color: #666;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .order-id-box {
            margin: 22px 0;
            background: #f0ebff;
            color: #4b2cff;
            border-radius: 18px;
            padding: 16px;
            font-size: 18px;
            font-weight: 900;
        }

        .success-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }

        .success-btn {
            padding: 12px 18px;
            border-radius: 20px;
            font-weight: 800;
            text-decoration: none;
        }

        .primary-btn {
            background: #6b4cff;
            color: #fff;
        }

        .primary-btn:hover {
            background: #5038e0;
            color: #fff;
            text-decoration: none;
        }

        .secondary-btn {
            background: #f0ebff;
            color: #6b4cff;
        }

        .secondary-btn:hover {
            background: #ded7ff;
            color: #4b2cff;
            text-decoration: none;
        }
    </style>
</head>

<body>
<div class="success-wrapper">
    <div class="success-card">

        <div class="success-icon">
            <span class="glyphicon glyphicon-ok"></span>
        </div>

        <h1>Order Placed!</h1>

        <p>Your order has been placed successfully.</p>
        <p>Thank you for shopping with us.</p>

        <div class="order-id-box">
            Order ID: #<?= htmlspecialchars($orderID) ?>
        </div>

        <div class="success-actions">
            <a href="home.php" class="success-btn secondary-btn">
                Continue Shopping
            </a>

            <a href="cartAction.php?action=placeOrder1" class="success-btn primary-btn">
                Go to Review →
            </a>
        </div>

    </div>
</div>
</body>
</html>