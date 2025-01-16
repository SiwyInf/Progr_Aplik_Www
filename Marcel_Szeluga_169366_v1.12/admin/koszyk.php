<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOSZYK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        td {
            background-color: #f9f9f9;
        }

        form {
            display: block;
            width: 100%;
            margin-top: 30px;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .actions a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .total {
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Twoje treści strony -->
    </div>
</body>
</html>


<?php
include('functions.php'); // Załaduj funkcje koszyka

// Obsługa akcji koszyka
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add_to_cart':
            if (isset($_GET['id'])) {
                $productId = intval($_GET['id']);
                $query = "SELECT tytul, cena_netto FROM products WHERE id = ?";
                if ($stmt = $link->prepare($query)) {
                    $stmt->bind_param("i", $productId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && mysqli_num_rows($result) > 0) {
                        $product = mysqli_fetch_assoc($result);
                        $productName = $product['tytul'];
                        $priceNetto = floatval($product['cena_netto']);
                        addToCart($productId, $productName, $priceNetto, 1);
                        header("Location: koszyk.php?action=show_cart");
                        exit();
                    } else {
                        echo "<p>Produkt nie istnieje.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p>Błąd w przygotowaniu zapytania.</p>";
                }
            }
            break;

        case 'remove_from_cart':
            if (isset($_GET['id'])) {
                $productId = intval($_GET['id']);
                removeFromCart($productId);
                header("Location: koszyk.php?action=show_cart");
                exit();
            }
            break;

        case 'edit_quantity':
            if (isset($_GET['id']) && isset($_POST['quantity'])) {
                $productId = intval($_GET['id']);
                $newQuantity = intval($_POST['quantity']);
                editQuantityInCart($productId, $newQuantity);
                header("Location: koszyk.php?action=show_cart");
                exit();
            }
            break;

        case 'show_cart':
            showCart();
            break;

        default:
            echo "<p>Nieznana akcja.</p>";
            break;
    }
} else {
    showCart();
}
?>
