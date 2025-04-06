<?php
$db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");

$user_id = $db->query("
SELECT user_id FROM loggedin WHERE id = 1
")->fetch(PDO::FETCH_COLUMN);

$username = $db->query("
    SELECT username FROM users WHERE id = $user_id
")->fetch(PDO::FETCH_COLUMN);

$login = ($username) ? strval($username) : "Login";

$data = $db->query("
    SELECT cart.user_id, cart.product_id, products.id, products.product_name, products.detail, products.price
    FROM cart
    INNER JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = $user_id AND cart.purchased = 1
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <title>CENG</title>
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

    main {
        margin: 20px;
    }

    section {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #333;
        color: white;
    }

    img {
        max-width: 100px;
        max-height: 100px;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .btn-purchase-all {
        display: block;
        margin: 0 auto;
        font-size: 1.5em;
        padding: 10px 20px;
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
    <main>
        <section>
            <h1>Orders</h1>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Product Name</th>
                        <th>Detail</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item) { ?>
                    <tr>
                        <td><img src="./product.png" alt="Product Image"></td>
                        <td><?= $item['product_name']; ?></td>
                        <td><?= $item['detail']; ?></td>
                        <td><?= $item['price']; ?> TL</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(".btn-remove-from-cart").click(function() {
        let id = $(this).data("id");
        deleteRecord(id);
    });

    function deleteRecord(id) {
        $.ajax({
            type: 'POST',
            url: 'api.php?p=c-delete',
            data: {
                id: id
            },
            success: function(result) {
                alert(result);
                window.location.href = 'cart.php';
            }
        });
    }
    $(".btn-purchase-all").click(function() {
        window.location.href = 'checkout.php';
    });
    </script>
</body>

</html>