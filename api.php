<?php
$db = new PDO("mysql:host=localhost;dbname=ceng-shop;charset=utf8", "root", "a3110z");

switch ($_GET['p']) {
    // products
    case "update":
        if($_POST){
            $id = $_POST['id'];
            $values = $_POST['values'];

            foreach ($values as $key => $value) {
                $stmt = $db->prepare("UPDATE products SET $key = :value WHERE id = :id");
                $stmt->bindParam(':value', $value);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }
            echo("Updated!");
        }
        break;
    case "delete":
        if ($_POST) {
            $db->query("DELETE FROM cart WHERE product_id = " . $_POST['id']);
            $db->query("DELETE FROM products WHERE id = " . $_POST['id']);
            echo("Deleted!");
        }
        break;
    case "add":
        if ($_POST) {
            $product_name = $_POST['product_name'];
            $price = $_POST['price'];
            $detail = $_POST['detail'];

            $db->query("INSERT INTO products (product_name, price, detail) VALUES ('$product_name', '$price', '$detail')");
            echo("Added!");
        }
        break;
    //cart    
    case "c-add":
        if ($_POST) {
            $user_id = $db->query("SELECT user_id FROM loggedin WHERE id = 1")->fetch(PDO::FETCH_COLUMN);
            $product_id = $_POST['product_id'];

            $db->query("INSERT INTO cart (user_id, product_id) VALUES ('$user_id', '$product_id')");
            echo("Product added to cart!");
        }
        break;
    case "c-delete":
        if ($_POST) {
            $user_id = $db->query("SELECT user_id FROM loggedin WHERE id = 1")->fetch(PDO::FETCH_COLUMN);
            $product_id = $_POST['id'];
            $db->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id ORDER BY id ASC LIMIT 1");
            echo("Cart Item Deleted!");
        }
        break;
    case "c-confirm":
        if ($_POST) {
            $user_id = $db->query("SELECT user_id FROM loggedin WHERE id = 1")->fetch(PDO::FETCH_COLUMN);
            
            $totalPriceQuery = $db->query("
            SELECT SUM(products.price) AS total_price
            FROM cart
            INNER JOIN products ON cart.product_id = products.id
            WHERE cart.user_id = $user_id AND cart.purchased = 0
            ")->fetch(PDO::FETCH_ASSOC);

            $db->query("UPDATE cart SET purchased = 1 WHERE user_id= $user_id AND purchased = 0");

            $totalPrice = $totalPriceQuery['total_price'];
            $bank = $_POST['bank'];
            $bank_account = $_POST['bank_account'];

            $db->prepare("INSERT INTO orders (user_id,  order_total, bank, bank_account) VALUES (?, ?, ?, ?)")
                    ->execute([$user_id, $totalPrice, $bank, $bank_account]);

            echo("Checkout Completed!");
        }
        break; 
    //auth       
    case "login":
        if ($_POST) {
            $username = $_POST['username'];
            $result = $db->prepare("SELECT id FROM users WHERE username = ?");
            $result->execute([$username]);
        
            if ($result->rowCount() == 0) {
                $username = $_POST['username'];
                $password = $_POST['password'];
        
                $db->prepare("INSERT INTO users (username,  password) VALUES (?, ?)")
                    ->execute([$username, $password]);
        
                $result = $db->prepare("SELECT id FROM users WHERE username = ?");
                $result->execute([$username]);
        
                if ($result->rowCount() > 0) {
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $user_id = $row['id'];
        
                    // loggedIn tablosundaki id'si 1 olan kaydın user_id'sini güncelleme
                    $db->prepare("UPDATE loggedIn SET user_id = ? WHERE id = 1")
                        ->execute([$user_id]);
        
                    echo("User Registered!");
                } else {
                    echo("User not found!");
                }
            } else {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['id'];
                $result = $db->prepare("SELECT id, password FROM users WHERE username = ?");
                $result->execute([$username]);
                $user_db = $result->fetch(PDO::FETCH_ASSOC);
                $password_db = $user_db['password'];
                $password = $_POST['password'];
                if ($password != $password_db) {
                    echo("Wrong Password!");
                }else{
                    $db->prepare("UPDATE loggedIn SET user_id = ? WHERE id = 1")
                    ->execute([$user_id]);
        
                echo("User loggedIn!");
                }
            }
        }
        break;    
    default:
        echo("Invalid action!");
        break;    
}

?>