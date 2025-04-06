<?php
$db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");
$data = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    div.row {
        border-bottom: 1px solid #ddd;
        margin-bottom: -1px;
    }

    input {
        margin: 5px;
        padding: 5px;
    }

    .btn {
        cursor: pointer;
    }

    #fixed-row {
        border-top: 2px solid #ccc;
        padding-top: 10px;
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
            <a href="login.php">Login</a>
            <a href="order.php">Orders</a>
            <a href="cart.php">Cart</a>
        </div>
    </nav>
    <div style="padding:16px">
        <?php foreach ($data as $item) { ?>
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control domain" value="<?= $item['product_name']; ?>"
                    data-id="<?= $item['id']; ?>" data-key="product_name" placeholder="Product Name" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control domain" value="<?= $item['price']; ?>"
                    data-id="<?= $item['id']; ?>" data-key="price" placeholder="Price" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control domain" value="<?= $item['detail']; ?>"
                    data-id="<?= $item['id']; ?>" data-key="detail" placeholder="Detail" readonly>
            </div>
            <div class="col-md-1">
                <button class="btn btn-info btn-edit" data-id="<?= $item['id']; ?>">Edit</button>
                <button class="btn btn-primary btn-update" data-id="<?= $item['id'];?>"
                    style="display:none;">Update</button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-danger btn-delete" data-id="<?= $item['id']; ?>">Delete</button>
            </div>
        </div>
        <?php } ?>

        <div class="row" id="fixed-row">
            <div class="col-md-3">
                <input type="text" id="new-name" class="form-control" placeholder="New Product Name">
            </div>
            <div class="col-md-3">
                <input type="text" id="new-price" class="form-control" placeholder="New Price">
            </div>
            <div class="col-md-3">
                <input type="text" id="new-detail" class="form-control" placeholder="New Detail">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success btn-add">Add</button>
                <a style="margin-left: 50px;color: black;" href="./db-schema.png" target="_blank">UML Schema</a>
                <a style="margin-left: 20px;color: black;" href="./er-diagram.png" target="_blank">ER Diagram</a>
                <a style="margin-left: 20px;color: black;" target="_blank"
                    href="https://1drv.ms/p/s!AtETk-n4ncX5oEM-ewgNqm2vIj0B?e=bzKc1r">PowerPoint</a>
            </div>
        </div>
    </div>

    <script>
    $(".btn-delete").addClass("btn-danger");

    $(".btn-edit").click(function() {
        let row = $(this).closest('.row');
        row.find('.domain').prop('readonly', false);
        $(this).hide();
        row.find('.btn-update').show();
    });

    $(".btn-update").click(function() {
        let row = $(this).closest('.row');
        let inputs = row.find('.domain');
        inputs.prop('readonly', true);
        $(this).hide();
        row.find('.btn-edit').show();
        updateRecord(inputs);
    });

    $(".btn-delete").click(function() {
        let id = $(this).data("id");
        deleteRecord(id);
    });

    $(".btn-add").click(function() {
        addRecord();
    });

    function updateRecord(inputs) {
        let id = inputs.eq(0).data("id");
        let values = {};
        inputs.each(function() {
            let key = $(this).data("key");
            let value = $(this).val();
            values[key] = value;
        });

        $.ajax({
            type: 'POST',
            url: 'api.php?p=update',
            data: {
                id,
                values
            },
            success: function(result) {
                alert(result);
            }
        });
    }

    function deleteRecord(id) {
        $.ajax({
            type: 'POST',
            url: 'api.php?p=delete',
            data: {
                id
            },
            success: function(result) {
                alert(result);
                window.location.href = 'admin.php';
            }
        });
    }

    function addRecord() {
        let newName = $("#new-name").val();
        let newPrice = $("#new-price").val();
        let newDetail = $("#new-detail").val();

        $.ajax({
            type: 'POST',
            url: 'api.php?p=add',
            data: {
                product_name: newName,
                price: newPrice,
                detail: newDetail
            },
            success: function(result) {
                alert(result);
                window.location.href = 'admin.php';
            }
        });
    }
    </script>

</body>

</html>