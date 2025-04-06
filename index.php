<?php
$db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");
$data = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
$user_id = $db->query("
SELECT user_id FROM loggedin WHERE id = 1
")->fetch(PDO::FETCH_COLUMN);
$username = $db->query("
    SELECT username FROM users WHERE id = $user_id
")->fetch(PDO::FETCH_COLUMN);
$login = ($username) ? strval($username) : "Login";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CENG</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    .btn {
        cursor: pointer;
    }

    .card {
        margin-bottom: 20px;
        margin-top: 20px;
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
    </style>
</head>

<body>
    <nav>
        <div class="left">
            <a href="admin.php">Admin</a>
            <a href="index.php">Home</a>
        </div>
        <div class="right">
            <a href="login.php"><?php echo $login; ?></a>
            <a href="order.php">Orders</a>
            <a href="cart.php">Cart</a>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <?php foreach ($data as $item) { ?>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="./product.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $item['product_name']; ?></h5>
                        <p class="card-text">Price: <?= $item['price']; ?></p>
                        <p class="card-text">Detail: <?= $item['detail']; ?></p>
                        <button class="btn btn-success btn-add-to-cart" data-id="<?= $item['id']; ?>">Add to
                            Cart</button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $(".btn-add-to-cart").click(function() {
        let productId = $(this).data("id");
        addToCart(productId);
    });

    function addToCart(productId) {
        $.ajax({
            type: 'POST',
            url: 'api.php?p=c-add',
            data: {
                product_id: productId
            },
            success: function(result) {
                alert(result);
            }
        });
    }
    </script>

</body>

</html>