<?php
// Dane do połączenia z bazą danych
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'moja_strona');

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Sprawdzenie połączenia
if (!$link) {
    die('<b>Przerwane połączenie: </b>' . mysqli_connect_error());
}

// Ustawienia administratora
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASS_HASH', password_hash('1234', PASSWORD_DEFAULT)); // Hasło haszowane

// Funkcje do generowania nagłówka i stopki
if (!function_exists('generujNaglowek')) {
    function generujNaglowek($tytul = "Panel Admina") {
        echo "<html><head><title>{$tytul}</title></head><body>";
        echo "<h1>{$tytul}</h1>";
        echo "<nav><a href='admin.php?action=list'>Lista Podstron</a> | <a href='admin.php?action=add'>Dodaj Podstronę</a> | <a href='admin.php?action=logout'>Wyloguj</a></nav><hr>";
    }
}

if (!function_exists('generujStopke')) {
    function generujStopke() {
        echo "<hr><footer>&copy; 2024 Panel Admina</footer></body></html>";
    }
}

/**
 * Wyświetla kategorie produktów.
 */
if (!function_exists('wyswietlKategorie')) {
    function wyswietlKategorie($link) {
        $query = "SELECT ID, category_name FROM category_list ORDER BY category_name";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='category-list'><h2>Kategorie</h2><ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                $category_name = htmlspecialchars($row['category_name']);
                $category_id = htmlspecialchars($row['ID']);
                echo "<li><a href='?category={$category_id}'>{$category_name}</a></li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p>Brak kategorii.</p>";
        }
    }
}

/**
 * Wyświetla produkty z wybranej kategorii.
 */
if (!function_exists('wyswietlProdukty')) {
    function wyswietlProdukty($link, $categoryId) {
        // Sprawdzenie, czy kategoria jest ustawiona i jest liczbą
        if (!isset($categoryId) || !is_numeric($categoryId)) {
            echo "<p>Niepoprawny ID kategorii.</p>";
            return;
        }

        $query = "SELECT p.ID, p.product_name, p.price, p.description, p.image_url 
                  FROM products p 
                  WHERE p.category_id = {$categoryId} ORDER BY p.product_name";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='product-list'><h2>Produkty w tej kategorii</h2><ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                $product_name = htmlspecialchars($row['product_name']);
                $price = htmlspecialchars($row['price']);
                $description = htmlspecialchars($row['description']);
                $image_url = htmlspecialchars($row['image_url']);
                $product_id = htmlspecialchars($row['ID']);
                
                echo "<li>";
                echo "<img src='{$image_url}' alt='{$product_name}' style='width:100px;height:auto;'><br>";
                echo "<strong>{$product_name}</strong><br>";
                echo "<p>{$description}</p>";
                echo "<p><b>Cena: </b>{$price} PLN</p>";
                echo "<a href='product.php?id={$product_id}'>Zobacz szczegóły</a>";
                echo "</li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p>Brak produktów w tej kategorii.</p>";
        }
    }
}
?>
