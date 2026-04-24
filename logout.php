<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logging Out...</title>

<!-- Auto redirect after 2 seconds -->
<meta http-equiv="refresh" content="2;url=index.php">

<link rel="stylesheet" href="styles.css">

<style>
.logout-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f3f6fb, #eef2f7);
}

.logout-card {
    width: 420px;
    background: #fff;
    border-radius: 28px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    animation: fadeIn 0.4s ease;
}

.logout-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    border-radius: 25px;
    background: linear-gradient(180deg, #4b2cff, #7a5cff);
    color: #fff;
    font-size: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logout-card h2 {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 10px;
}

.logout-card p {
    color: #777;
    margin-bottom: 20px;
}

.logout-btn {
    display: block;
    padding: 12px;
    border-radius: 20px;
    background: #6b4cff;
    color: #fff;
    font-weight: 700;
    text-decoration: none;
}

.logout-btn:hover {
    background: #5038e0;
    color: #fff;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>
</head>

<body>

<div class="logout-wrapper">
    <div class="logout-card">

        <div class="logout-icon">✓</div>

        <h2>Logged Out Successfully</h2>
        <p>Redirecting to login page...</p>

        <a href="index.php" class="logout-btn">Go Now</a>

    </div>
</div>

</body>
</html>