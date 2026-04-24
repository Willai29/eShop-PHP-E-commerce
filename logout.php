<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logged Out</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        .logout-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .logout-card {
            width: 430px;
            background: #fff;
            border-radius: 28px;
            padding: 42px 35px;
            text-align: center;
            box-shadow: 0 18px 45px rgba(0,0,0,0.12);
        }

        .logout-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 18px;
            border-radius: 24px;
            background: linear-gradient(180deg, #4b2cff, #7a5cff);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }

        .logout-card h1 {
            color: #222;
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .logout-card p {
            color: #777;
            margin: 0 0 26px;
            font-size: 15px;
        }

        .logout-btn {
            display: block;
            background: #6b4cff;
            color: #fff;
            border-radius: 20px;
            padding: 12px 18px;
            font-weight: 800;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #5038e0;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>
<div class="logout-wrapper">
    <div class="logout-card">
        <div class="logout-icon">
            <span class="glyphicon glyphicon-ok"></span>
        </div>

        <h1>Thanks for stopping by</h1>
        <p>You have been logged out successfully.</p>

        <a href="index.php" class="logout-btn">Back to Home</a>
    </div>
</div>
</body>
</html>