<?php
session_start();
require_once 'cfg.php'; 

// Połączenie z bazą danych MySQL
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$link) {
    die('<b>Przerwane połączenie: </b>' . mysqli_connect_error());
}

/**
 * Generuje nagłówek strony admina.
 */
function generujNaglowek($tytul = "Panel Admina") {
    echo "<html><head><title>{$tytul}</title></head><body>";
    echo "<h1>{$tytul}</h1>";
    echo "<nav><a href='admin.php?action=list_categories'>Lista Kategorii</a> | 
              <a href='admin.php?action=add_category'>Dodaj Kategorię</a> | 
              <a href='admin.php?action=list_products'>Lista Produktów</a> | 
              <a href='admin.php?action=add_product'>Dodaj Produkt</a> | 
              <a href='admin.php?action=logout'>Wyloguj</a></nav><hr>";
}



/**
 * Generuje stopkę strony admina.
 */
function generujStopke() {
    echo "<hr><footer>&copy; 2024 Panel Admina</footer></body></html>";
	echo "<a href='koszyk.php?action=show_cart'>Koszyk</a> | ";

}

// Kod kategorii pozostaje bez zmian...

/**
 * Wyświetla listę kategorii.
 */
function listaKategorii($link) {
    $query = "SELECT ID, nazwa, matka FROM category_list ORDER BY matka, nazwa";
    $result = mysqli_query($link, $query);

    echo "<div class='category-list'><h1>Lista Kategorii</h1>";
    echo "<a href='admin.php?action=add_category'>Dodaj Nową Kategorię</a><br><br>";
    echo "<table border='1'><tr><th>ID</th><th>Nazwa Kategorii</th><th>Akcje</th></tr>";

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
	

    // Wyświetlanie kategorii głównych
    foreach ($categories as $category) {
        if ($category['matka'] == 0) {
            echo "<tr><td>{$category['ID']}</td><td>{$category['nazwa']}</td>";
            echo "<td><a href='admin.php?action=edit_category&id={$category['ID']}'>Edytuj</a> | ";
            echo "<a href='admin.php?action=delete_category&id={$category['ID']}' onclick=\"return confirm('Czy na pewno chcesz usunąć tę kategorię?')\">Usuń</a></td></tr>";

            // Wyświetlanie podkategorii
            foreach ($categories as $subCategory) {
                if ($subCategory['matka'] == $category['ID']) {
                    echo "<tr><td>&nbsp;&nbsp;&nbsp;{$subCategory['ID']}</td><td>{$subCategory['nazwa']}</td>";
                    echo "<td><a href='admin.php?action=edit_category&id={$subCategory['ID']}'>Edytuj</a> | ";
                    echo "<a href='admin.php?action=delete_category&id={$subCategory['ID']}' onclick=\"return confirm('Czy na pewno chcesz usunąć tę podkategorię?')\">Usuń</a></td></tr>";
                }
            }
        }
    }

    echo "</table></div>";
}


/**
 * Wyświetla formularz dodawania nowego produktu.
 */
function dodajProduktFormularz() {
    echo "<div class='add-product-form'><h1>Dodaj Nowy Produkt</h1>";
    echo "<form method='post' action='admin.php?action=process_add_product'>";
    echo "<label for='product_title'>Tytuł Produktu:</label><br>";
    echo "<input type='text' name='product_title' id='product_title' required><br><br>";
    echo "<label for='product_description'>Opis Produktu:</label><br>";
    echo "<textarea name='product_description' id='product_description' required></textarea><br><br>";
    echo "<label for='product_price'>Cena Netto:</label><br>";
    echo "<input type='number' step='0.01' name='product_price' id='product_price' required><br><br>";
    echo "<label for='product_stock'>Ilość na magazynie:</label><br>";
    echo "<input type='number' name='product_stock' id='product_stock' required><br><br>";
    echo "<label for='product_status'>Status dostępności:</label><br>";
    echo "<select name='product_status' id='product_status'><option value='Dostępny'>Dostępny</option><option value='Niedostępny'>Niedostępny</option></select><br><br>";
    echo "<input type='submit' value='Dodaj Produkt'></form></div>";
}

/**
 * Wyświetla formularz dodawania nowej kategorii.
 */
function dodajKategorieFormularz($parent_id = 0) {
    echo "<div class='add-category-form'><h1>Dodaj Nową Kategorię</h1>";
    echo "<form method='post' action='admin.php?action=process_add_category'>";
    echo "<label for='category_name'>Nazwa Kategorii:</label><br>";
    echo "<input type='text' name='category_name' id='category_name' required><br><br>";

    if ($parent_id != 0) {
        echo "<input type='hidden' name='parent_id' value='{$parent_id}'>";
    }

    echo "<input type='submit' value='Dodaj Kategorię'></form></div>";
}

/**
 * Wyświetla listę produktów.
 */
function listaProduktow($link) {
    $query = "SELECT id, tytul, cena_netto, ilosc, status_dostepnosci FROM products ORDER BY tytul";
    $result = mysqli_query($link, $query);

    // Sprawdzenie, czy zapytanie SQL się powiodło
    if (!$result) {
        die("Błąd zapytania SQL: " . mysqli_error($link));
    }

    echo "<div class='product-list'><h1>Lista Produktów</h1>";
    echo "<a href='admin.php?action=add_product'>Dodaj Nowy Produkt</a><br><br>";
    echo "<table border='1'><tr><th>ID</th><th>Tytuł</th><th>Cena netto</th><th>Ilość</th><th>Status</th><th>Akcje</th><th>Koszyk</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['tytul']}</td><td>{$row['cena_netto']}</td><td>{$row['ilosc']}</td><td>{$row['status_dostepnosci']}</td>";
        echo "<td><a href='admin.php?action=edit_product&id={$row['id']}'>Edytuj</a> | ";
        echo "<a href='admin.php?action=delete_product&id={$row['id']}' onclick=\"return confirm('Czy na pewno chcesz usunąć ten produkt?')\">Usuń</a></td>";
        echo "<td><a href='koszyk.php?action=add_to_cart&id={$row['id']}'>Dodaj do koszyka</a></td></tr>";
    }

    echo "</table></div>";
}





/**
 * Dodaje nowy produkt do bazy danych.
 */
function procesDodawaniaProduktu($link) {
    if (isset($_POST['product_title'])) {
        $product_title = mysqli_real_escape_string($link, $_POST['product_title']);
        $product_description = mysqli_real_escape_string($link, $_POST['product_description']);
        $product_price = $_POST['product_price'];
        $product_stock = $_POST['product_stock'];
        $product_status = $_POST['product_status'];

        $query = "INSERT INTO products (tytul, opis, cena_netto, ilosc, status_dostepnosci) 
                  VALUES ('{$product_title}', '{$product_description}', {$product_price}, {$product_stock}, '{$product_status}')";
        mysqli_query($link, $query);
        header("Location: admin.php?action=list_products");
        exit();
    }
}

/**
 * Usuwa kategorię z bazy danych.
 */
function usunKategorie($link, $id) {
    // Sprawdź, czy kategoria istnieje w bazie
    $query = "SELECT * FROM category_list WHERE ID = {$id}";
    $result = mysqli_query($link, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Jeśli kategoria istnieje, usuń ją z bazy danych
        $query = "DELETE FROM category_list WHERE ID = {$id}";
        mysqli_query($link, $query);
        
        // Po usunięciu przekierowanie do listy kategorii
        header("Location: admin.php?action=list_categories");
        exit();
    } else {
        // Kategoria o takim ID nie istnieje
        echo "<p>Nie znaleziono kategorii o podanym ID.</p>";
    }
}


/**
 * Usuwa produkt.
 */
function usunProdukt($link, $id) {
    $query = "DELETE FROM products WHERE id = {$id}";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list_products');
    exit();
}

/**
 * Formularz edycji kategorii.
 */
function edytujKategorieFormularz($link, $id) {
    $query = "SELECT * FROM category_list WHERE ID = {$id}";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $category_name = htmlspecialchars($row['nazwa']);
        $parent_id = $row['matka'];

        echo "<div class='edit-category-form'><h1>Edytuj Kategorię</h1>";
        echo "<form method='post' action='admin.php?action=process_edit_category&id={$id}'>";
        echo "<label for='category_name'>Nazwa Kategorii:</label><br>";
        echo "<input type='text' name='category_name' id='category_name' value='{$category_name}' required><br><br>";
        echo "<input type='hidden' name='parent_id' value='{$parent_id}'>";
        echo "<input type='submit' value='Zapisz Zmiany'></form></div>";
    } else {
        echo "<p>Nie znaleziono kategorii o podanym ID.</p>";
    }
}

/**
 * Dodaje nową kategorię do bazy danych.
 */
function procesDodawaniaKategorii($link) {
    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0; // Jeśli nie ma parent_id, ustaw na 0

        // Zapytanie SQL do dodania nowej kategorii
        $query = "INSERT INTO category_list (nazwa, matka) VALUES ('{$category_name}', {$parent_id})";
        mysqli_query($link, $query);

        // Po zapisaniu przekierowanie do listy kategorii
        header("Location: admin.php?action=list_categories");
        exit();
    }
}

/**
 * Proces edytowania kategorii.
 */
function procesEdycjiKategorii($link, $id) {
    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = $_POST['parent_id'];

        // Aktualizacja nazwy kategorii
        $query = "UPDATE category_list SET nazwa = '{$category_name}', matka = '{$parent_id}' WHERE ID = {$id}";
        mysqli_query($link, $query);

        // Po zapisaniu przekierowanie do listy kategorii
        header("Location: admin.php?action=list_categories");
        exit();
    }
}


/**
 * Formularz edycji produktu.
 */
function edytujProduktFormularz($link, $id) {
    $query = "SELECT * FROM products WHERE id = {$id}";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $product_title = htmlspecialchars($row['tytul']);
        $product_description = htmlspecialchars($row['opis']);
        $product_price = $row['cena_netto'];
        $product_stock = $row['ilosc'];
        $product_status = $row['status_dostepnosci'];

        echo "<div class='edit-product-form'><h1>Edytuj Produkt</h1>";
        echo "<form method='post' action='admin.php?action=process_edit_product&id={$id}'>";
        echo "<label for='product_title'>Tytuł Produktu:</label><br>";
        echo "<input type='text' name='product_title' id='product_title' value='{$product_title}' required><br><br>";
        echo "<label for='product_description'>Opis Produktu:</label><br>";
        echo "<textarea name='product_description' id='product_description' required>{$product_description}</textarea><br><br>";
        echo "<label for='product_price'>Cena Netto:</label><br>";
        echo "<input type='number' step='0.01' name='product_price' id='product_price' value='{$product_price}' required><br><br>";
        echo "<label for='product_stock'>Ilość na magazynie:</label><br>";
        echo "<input type='number' name='product_stock' id='product_stock' value='{$product_stock}' required><br><br>";
        echo "<label for='product_status'>Status dostępności:</label><br>";
        echo "<select name='product_status' id='product_status'><option value='Dostępny' ".($product_status == 'Dostępny' ? 'selected' : '').">Dostępny</option><option value='Niedostępny' ".($product_status == 'Niedostępny' ? 'selected' : '').">Niedostępny</option></select><br><br>";
        echo "<input type='submit' value='Zapisz Zmiany'></form></div>";
    } else {
        echo "<p>Nie znaleziono produktu o podanym ID.</p>";
    }
}

/**
 * Proces edytowania produktu.
 */
function procesEdycjiProduktu($link, $id) {
    if (isset($_POST['product_title'])) {
        $product_title = mysqli_real_escape_string($link, $_POST['product_title']);
        $product_description = mysqli_real_escape_string($link, $_POST['product_description']);
        $product_price = $_POST['product_price'];
        $product_stock = $_POST['product_stock'];
        $product_status = $_POST['product_status'];

        $query = "UPDATE products SET tytul = '{$product_title}', opis = '{$product_description}', cena_netto = {$product_price}, ilosc = {$product_stock}, status_dostepnosci = '{$product_status}' WHERE id = {$id}";
        mysqli_query($link, $query);
        header("Location: admin.php?action=list_products");
        exit();
    }
}
// Funkcja wyświetlająca listę podstron
function ListaPodstron($link) {
    $query = "SELECT ID, page_title FROM page_list ORDER BY ID DESC";
    $result = mysqli_query($link, $query);
    $output = '<div class="subpage-list"><h1>Lista Podstron</h1>';
    $output .= '<a href="admin.php?action=add">Dodaj Nową Podstronę</a><br><br>';
    $output .= '<table border="1"><tr><th>ID</th><th>Tytuł Podstrony</th><th>Akcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr><td>' . $row['ID'] . '</td>';
        $output .= '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        $output .= '<td><a href="admin.php?action=edit&id=' . $row['ID'] . '">Edytuj</a> | ';
        $output .= '<a href="admin.php?action=delete&id=' . $row['ID'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a></td></tr>';
    }
    $output .= '</table></div>';
    return $output;
}

// Funkcja dodawania nowej podstrony
function DodajNowaPodstrone() {
    $output = '<div class="add-subpage-form"><h1>Dodaj Nową Podstronę</h1>';
    $output .= '<form method="post" action="admin.php?action=process_add">';
    $output .= '<label for="page_title">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="page_title" id="page_title" required><br><br>';
    $output .= '<label for="page_content">Treść Podstrony:</label><br>';
    $output .= '<textarea name="page_content" id="page_content" rows="10" cols="50" required></textarea><br><br>';
    $output .= '<label for="status">Aktywna:</label>';
    $output .= '<input type="checkbox" name="status" id="status" value="1"><br><br>';
    $output .= '<input type="submit" value="Dodaj Podstronę"></form></div>';
    return $output;
}

// Obsługa dodawania podstrony
if (isset($_GET['action']) && $_GET['action'] === 'process_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($link, $_POST['page_title']);
    $content = mysqli_real_escape_string($link, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}

// Funkcja edytowania podstrony
function EdytujPodstrone($link, $id) {
    $query = "SELECT page_title, page_content, status FROM page_list WHERE ID = " . intval($id);
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $output = '<div class="edit-subpage-form"><h1>Edytuj Podstronę</h1>';
    $output .= '<form method="post" action="admin.php?action=process_edit&id=' . intval($id) . '">';
    $output .= '<label for="page_title">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="page_title" value="' . htmlspecialchars($row['page_title']) . '" required><br><br>';
    $output .= '<label for="page_content">Treść Podstrony:</label><br>';
    $output .= '<textarea name="page_content" rows="10" cols="50" required>' . htmlspecialchars($row['page_content']) . '</textarea><br><br>';
    $output .= '<label for="status">Aktywna:</label>';
    $output .= '<input type="checkbox" name="status" value="1"' . ($row['status'] ? ' checked' : '') . '><br><br>';
    $output .= '<input type="submit" value="Zapisz zmiany"></form></div>';
    return $output;
}

// Obsługa edycji podstrony
if (isset($_GET['action']) && $_GET['action'] === 'process_edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_GET['id']);
    $title = mysqli_real_escape_string($link, $_POST['page_title']);
    $content = mysqli_real_escape_string($link, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = $status WHERE ID = $id";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}

// Funkcja usuwania podstrony
function UsunPodstrone($link, $id) {
    $query = "DELETE FROM page_list WHERE ID = " . intval($id);
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}


// Sprawdzanie logowania
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    generujNaglowek();

    // Obsługa akcji
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            // Kategoria
            case 'list_categories':
                listaKategorii($link);
                break;
            case 'add_category':
                dodajKategorieFormularz(isset($_GET['parent']) ? $_GET['parent'] : 0);
                break;
            case 'process_add_category':
                procesDodawaniaKategorii($link);
                break;
            case 'edit_category':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    edytujKategorieFormularz($link, $_GET['id']);
                }
                break;
            case 'process_edit_category':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    procesEdycjiKategorii($link, $_GET['id']);
                }
                break;
            case 'delete_category':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    usunKategorie($link, $_GET['id']);
                }
                break;
            // Produkty
            case 'list_products':
                listaProduktow($link);
                break;
            case 'add_product':
                dodajProduktFormularz();
                break;
            case 'process_add_product':
                procesDodawaniaProduktu($link);
                break;
            case 'edit_product':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    edytujProduktFormularz($link, $_GET['id']);
                }
                break;
            case 'process_edit_product':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    procesEdycjiProduktu($link, $_GET['id']);
                }
                break;
            case 'delete_product':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    usunProdukt($link, $_GET['id']);
                }
                break;
            case 'logout':
                session_destroy();
                header('Location: admin.php');
                exit();
            default:
                echo '<p>Nieznana akcja.</p>';
                break;
        }
    } else {
        echo "<p>Wybierz akcję z menu nawigacyjnego.</p>";
    }

// Sprawdzanie sesji logowania
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Obsługa akcji w panelu administracyjnym


    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'list':
                echo ListaPodstron($link);
                break;

            case 'add':
                echo DodajNowaPodstrone();
                break;

            case 'edit':
                if (isset($_GET['id'])) {
                    echo EdytujPodstrone($link, $_GET['id']);
                } else {
                    echo '<p>Brak ID podstrony do edycji.</p>';
                }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    UsunPodstrone($link, $_GET['id']);
                } else {
                    echo '<p>Brak ID podstrony do usunięcia.</p>';
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: admin.php');
                exit();

            default:
                echo '<p>Nieznana akcja.</p>';
                break;
        }
    } else {
        echo ListaPodstron($link);
    }
} else {
    if (isset($_POST['submit'])) {
        $login = $_POST['login'];
        $pass = $_POST['pass'];

        // Sprawdzanie loginu i hasła
        if ($login === ADMIN_LOGIN && password_verify($pass, ADMIN_PASS_HASH)) {
            $_SESSION['logged_in'] = true;
            header('Location: admin.php');
            exit();
        } else {
            echo '<p>Nieprawidłowy login lub hasło.</p>';
        }
    }
    FormularzLogowania();
}

    generujStopke();
} else {
    // Wyświetlanie formularza logowania
    echo '<form method="POST" action="admin.php">';
    echo '<label for="login">Login:</label>';
    echo '<input type="text" id="login" name="login" required>';
    echo '<label for="pass">Hasło:</label>';
    echo '<input type="password" id="pass" name="pass" required>';
    echo '<button type="submit" name="submit">Zaloguj</button>';
    echo '</form>';

    // Sprawdzenie logowania
    if (isset($_POST['login']) && isset($_POST['pass'])) {
        $login = $_POST['login'];
        $pass = $_POST['pass'];

        if ($login === ADMIN_LOGIN && password_verify($pass, ADMIN_PASS_HASH)) {
            $_SESSION['logged_in'] = true;
            header('Location: admin.php');
            exit();
        } else {
            echo '<p>Nieprawidłowy login lub hasło.</p>';
        }
    } else {
        echo '<p>Proszę wypełnić oba pola.</p>';
    }
}



?>
<!-- HTML do przycisku -->
<form action="admin.php" method="get">
    <button type="submit">Menu</button>
</form>
