<?php
        $transactionDateTime = date('d.m.Y H:i:s');
        $transactionDateTime = date('d.m.Y H:i:s', strtotime($transactionDateTime . ' +2 hours'));
        $db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");
        $user_id = $db->query("SELECT user_id FROM loggedin WHERE id = 1
        ")->fetch(PDO::FETCH_COLUMN);

        $username = $db->query("
            SELECT username FROM users WHERE id = $user_id
        ")->fetch(PDO::FETCH_COLUMN);

        $totalPriceQuery = $db->query("
        SELECT SUM(products.price) AS total_price
        FROM cart
        INNER JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = $user_id AND cart.purchased = 0
        ")->fetch(PDO::FETCH_ASSOC);

        $totalPrice = $totalPriceQuery['total_price'];
    ?>



<!DOCTYPE html>
<html lang="tr">

<head>
    <title>CENG</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    header {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 20px;
        margin-bottom: 20px;
    }

    main {
        width: 300px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    section {
        margin-bottom: 20px;
    }

    h3 {
        color: #333;
    }

    p {
        margin: 0;
        font-size: 18px;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-top: 8px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .cancel {
        background-color: red;
    }

    .cancel:hover {
        background-color: black;

    }
    </style>
</head>

<body>
    <header>
        <h1>Purchase Confirm</h1>
    </header>
    <main>
        <section>
            <h3>İşlem Tarihi-Saati:</h3>
            <p><?php echo $transactionDateTime; ?></p>
        </section>
        <section>
            <h3>İşlem Tutarı:</h3>
            <p><?php echo $totalPrice; ?>$</p>
        </section>
        <section>
            <h3>Bank Name:</h3>
            <input type="text" id="bank" placeholder="Bank Name">
        </section>
        <section>
            <h3>Bank Account:</h3>
            <input type="text" id="bank_account" placeholder="Bank Account">
        </section>
        <section>
            <button type="button" id="confirm" onclick="confirmPurchase()">Confirm</button>
            <button type="button" class="cancel" id="cancel" onclick="cancelPurchase()">Cancel</button>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    function confirmPurchase() {
        var bank = $("#bank").val();
        var bank_account = $("#bank_account").val();
        $.ajax({
            type: 'POST',
            url: 'api.php?p=c-confirm',
            data: {
                bank: bank,
                bank_account: bank_account
            },
            success: function(result) {
                alert(result);
                window.location.href = 'order.php';
            }
        });
    }

    function cancelPurchase() {
        window.location.href = 'cart.php';
    }
    </script>
</body>

</html>