<?php
session_start();
require_once '../cfg.php'; 

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
}

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
 * Dodaje nową kategorię do bazy danych.
 */
function procesDodawaniaKategorii($link) {
    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

        $query = "INSERT INTO category_list (nazwa, matka) VALUES ('{$category_name}', {$parent_id})";
        mysqli_query($link, $query);
        header("Location: admin.php?action=list_categories");
        exit();
    }
}

/**
 * Usuwa kategorię.
 */
function usunKategorie($link, $id) {
    $query = "DELETE FROM category_list WHERE ID = {$id}";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list_categories');
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
 * Proces edytowania kategorii.
 */
function procesEdycjiKategorii($link, $id) {
    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = $_POST['parent_id'];

        // Aktualizacja nazwy kategorii
        $query = "UPDATE category_list SET nazwa = '{$category_name}' WHERE ID = {$id}";
        mysqli_query($link, $query);
        header("Location: admin.php?action=list_categories");
        exit();
    }
}

// Sprawdzanie logowania
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    generujNaglowek();

    // Obsługa akcji
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
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
    if (isset($_POST['submit'])) {
        $login = $_POST['login'];
        $pass = $_POST['pass'];

        if ($login === ADMIN_LOGIN && password_verify($pass, ADMIN_PASS_HASH)) {
            $_SESSION['logged_in'] = true;
            header('Location: admin.php');
            exit();
        } else {
            echo '<p>Nieprawidłowy login lub hasło.</p>';
        }
    }
}
?>
