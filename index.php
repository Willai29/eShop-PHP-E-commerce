<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        require 'login.php';
    } elseif (isset($_POST['register'])) {
        require 'register.php';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign-Up/Login</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .auth-card {
            width: 900px;
            min-height: 560px;
            background: #fff;
            border-radius: 32px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }

        .auth-left {
            background: linear-gradient(180deg, #4b2cff, #7a5cff);
            color: #fff;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-left h1 {
            color: #fff;
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .auth-left p {
            color: rgba(255,255,255,0.85);
            font-size: 15px;
            line-height: 1.6;
        }

        .auth-right {
            padding: 38px;
        }

        .auth-tabs {
            display: flex;
            background: #f1efff;
            padding: 6px;
            border-radius: 18px;
            margin-bottom: 28px;
        }

        .auth-tabs a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 14px;
            font-weight: 800;
            color: #6b4cff;
            text-decoration: none;
        }

        .auth-tabs .active a {
            background: #6b4cff;
            color: #fff;
        }

        .auth-content h2 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 22px;
            color: #222;
        }

        .auth-field {
            margin-bottom: 14px;
        }

        .auth-field label {
            position: static;
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 6px;
            pointer-events: auto;
        }

        .auth-field input {
            width: 100%;
            height: auto;
            background: #f8f9ff;
            color: #222;
            border: 1px solid #e1e4ee;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .auth-btn {
            width: 100%;
            border: none;
            background: #6b4cff;
            color: #fff;
            padding: 13px;
            border-radius: 18px;
            font-weight: 900;
            margin-top: 10px;
        }

        .auth-btn:hover {
            background: #5038e0;
        }

        #signup {
            display: none;
        }

        @media (max-width: 850px) {
            .auth-card {
                grid-template-columns: 1fr;
            }

            .auth-left {
                display: none;
            }

            .auth-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
<div class="auth-page">
    <div class="auth-card">

        <div class="auth-left">
            <h1>E commerce</h1>
            <p>
                Manage your shopping experience.
            </p>
        </div>

        <div class="auth-right">
            <ul class="auth-tabs tab-group">
                <li class="tab"><a href="#signup">Sign Up</a></li>
                <li class="tab active"><a href="#login">Log In</a></li>
            </ul>

            <div class="auth-content tab-content">

                <div id="login">
                    <h2>Welcome Back!</h2>

                    <form action="index.php" method="post" autocomplete="off">
                        <div class="auth-field">
                            <label>Email Address *</label>
                            <input type="email" required autocomplete="off" name="email">
                        </div>

                        <div class="auth-field">
                            <label>Password *</label>
                            <input type="password" required autocomplete="off" name="password">
                        </div>

                        <button type="submit" class="auth-btn" name="login">Log In</button>
                    </form>
                </div>

                <div id="signup">
                    <h2>Create Account</h2>

                    <form action="index.php" method="post" autocomplete="off">
                        <div class="auth-row">
                            <div class="auth-field">
                                <label>First Name *</label>
                                <input type="text" required autocomplete="off" name="firstname">
                            </div>

                            <div class="auth-field">
                                <label>Last Name *</label>
                                <input type="text" required autocomplete="off" name="lastname">
                            </div>
                        </div>

                        <div class="auth-field">
                            <label>Email Address *</label>
                            <input type="email" required autocomplete="off" name="email">
                        </div>

                        <div class="auth-field">
                            <label>Address *</label>
                            <input type="text" required autocomplete="off" name="address">
                        </div>

                        <div class="auth-field">
                            <label>Phone Number *</label>
                            <input type="tel" required autocomplete="off" name="phone">
                        </div>

                        <div class="auth-field">
                            <label>Password *</label>
                            <input type="password" required autocomplete="off" name="password">
                        </div>

                        <button type="submit" class="auth-btn" name="register">Register</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/index.js"></script>
</body>
</html>