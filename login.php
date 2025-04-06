<?php
$db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CENG</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    nav {
        background-color: #333;
        overflow: hidden;
        line-height: 50px;
    }

    nav a {
        color: white;
        text-align: center;
        padding: 20px 20px;
        text-decoration: none;
    }

    nav a:hover {
        background-color: #ddd;
        color: black;
    }

    .left {
        float: left;
    }

    .right {
        float: right;
    }

    .login-container {
        background-color: white;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    button {
        padding: 10px 15px;
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <nav>
        <div class="left">
            <a href="admin.php">Admin</a>
            <a href="index.php">Home</a>
        </div>
        <div class="right">
            <a href="login.php">Login</a>
            <a href="order.php">Orders</a>
            <a href="cart.php">Cart</a>
        </div>
    </nav>
    <div class="login-container">
        <h2>Login</h2>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">
        </div>

        <button id="loginButton">Login</button>
    </div>

    <script>
    $(document).ready(function() {
        $("#loginButton").click(function() {
            var username = $("#username").val();
            var password = $("#password").val();

            $.ajax({
                type: 'POST',
                url: 'api.php?p=login',
                data: {
                    username: username,
                    password: password,
                },
                success: function(result) {
                    alert(result);
                    if (result == "User loggedIn!" || result == "User Registered!") {
                        window.location.href = 'index.php';
                    }
                }
            });
        });
    });
    </script>
</body>

</html>