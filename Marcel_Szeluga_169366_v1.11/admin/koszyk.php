<?php
session_start();
include('cfg.php'); // Połączenie z bazą danych

// Funkcja dodająca produkt do koszyka
function addToCart($productId, $productName, $priceNetto, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price_netto' => $priceNetto,
            'quantity' => $quantity,
            'price_brutto' => round($priceNetto * 1.23, 2) // Cena brutto
        ];
    }
}

// Funkcja do pokazania koszyka
function showCart() {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        echo "<h1>Koszyk</h1>";
        echo "<table border='1'>";
        echo "<tr><th>Produkt</th><th>Cena Netto</th><th>Cena Brutto</th><th>Ilość</th><th>Suma Brutto</th><th>Akcje</th></tr>";

        $total = 0;
        foreach ($_SESSION['cart'] as $productId => $item) {
            $sum = round($item['price_brutto'] * $item['quantity'], 2);
            $total += $sum;

            echo "<tr>";
            echo "<td>{$item['name']}</td>";
            echo "<td>{$item['price_netto']} zł</td>";
            echo "<td>{$item['price_brutto']} zł</td>";
            echo "<td>";
            echo "<form method='post' action='koszyk.php?action=edit_quantity&id={$productId}'>";
            echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
            echo "<button type='submit'>Zmień ilość</button>";
            echo "</form>";
            echo "</td>";
            echo "<td>{$sum} zł</td>";
            echo "<td><a href='koszyk.php?action=remove_from_cart&id={$productId}'>Usuń</a></td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='4'>Razem:</td><td>{$total} zł</td><td></td></tr>";
        echo "</table>";

        // Sekcja płatności
        showPaymentForm($total);
    } else {
        echo "<p>Koszyk jest pusty.</p>";
    }
}

// Funkcja do usunięcia produktu z koszyka
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Funkcja do edytowania ilości w koszyku
function editQuantityInCart($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        if ($newQuantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            removeFromCart($productId);
        }
    }
}

// Funkcja do generowania formularza płatności z dostawą
function showPaymentForm($total) {
    if ($total > 0) {
        echo "<h2>Przejdź do płatności</h2>";
        echo "<form method='post' action='platnosc.php'>";

        // Pole z ukrytą wartością sumy zamówienia
        echo "<input type='hidden' name='total' value='{$total}'>";

        // Formularz z adresem dostawy
        echo "<h3>Adres dostawy</h3>";
        echo "<label for='name'>Imię i nazwisko:</label>";
        echo "<input type='text' name='name' id='name' required><br><br>";

        echo "<label for='address'>Adres:</label>";
        echo "<input type='text' name='address' id='address' required><br><br>";

        echo "<label for='city'>Miasto:</label>";
        echo "<input type='text' name='city' id='city' required><br><br>";

        echo "<label for='zip_code'>Kod pocztowy:</label>";
        echo "<input type='text' name='zip_code' id='zip_code' pattern='[0-9]{2}-[0-9]{3}' title='Format: 00-000' required><br><br>";

        echo "<label for='phone'>Numer telefonu:</label>";
        echo "<input type='tel' name='phone' id='phone' required><br><br>";

        // Opcje dostawy
        echo "<h3>Opcje dostawy</h3>";
        echo "<select name='delivery_method' id='delivery_method' required>";
        echo "<option value='courier'>Kurier</option>";
        echo "<option value='parcel_locker'>Paczkomat</option>";
        echo "<option value='in_store'>Odbiór w sklepie</option>";
        echo "</select><br><br>";

        // Wybór metody płatności
        echo "<h3>Metoda płatności</h3>";
        echo "<select name='payment_method' id='payment_method' required>";
        echo "<option value='card'>Karta płatnicza</option>";
        echo "<option value='blik'>BLIK</option>";
        echo "<option value='paypal'>PayPal</option>";
        echo "</select><br><br>";

        // Przycisk wysyłający formularz
        echo "<button type='submit'>Zapłać</button>";

        echo "</form>";
    }
}

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
}
?>
