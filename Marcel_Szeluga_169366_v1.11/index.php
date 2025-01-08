<?php
error_reporting(E_ALL);
include('admin/cfg.php');  // Dołączenie pliku konfiguracyjnego z połączeniem do bazy danych

// Sprawdzanie połączenia z bazą danych
if (mysqli_connect_errno()) {
    echo "Błąd połączenia z bazą danych: " . mysqli_connect_error();
    exit;
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <title>Moje hobby to</title>
    <style>
        .test-block {
            width: 200px;
            height: 50px;
            background-color: lightblue;
            text-align: center;
            line-height: 50px;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body onload="startclock()">
    <nav>
        <ul>
            <li><a href="index.php?page_id=1">Menu</a></li>
            <li><a href="index.php?page_id=2">Kontakt</a></li>
            <li><a href="index.php?page_id=3">Wędki</a></li>
            <li><a href="index.php?page_id=4">Kołowrotki</a></li>
            <li><a href="index.php?page_id=5">Plecionki</a></li>
            <li><a href="index.php?page_id=6">Przynęty</a></li>
            <li><a href="index.php?page_id=7">Rekordy</a></li>
            <li><a href="index.php?page_id=8">Filmy</a></li>
        </ul>
    </nav>

    <!-- Animacje -->
    <div id="animacjaTestowa1" class="test-block">Kliknij, a się powiększę</div>
    <script>
        $("#animacjaTestowa1").on("click", function () {
            $(this).animate({
                width: "500px",
                opacity: 0.4,
                fontSize: "3em",
                borderWidth: "10px"
            }, 1500);
        });
    </script>

    <div id="animacjaTestowa2" class="test-block">Najedź kursorem, a się powiększę</div>
    <script>
        $("#animacjaTestowa2").on({
            "mouseover": function () {
                $(this).animate({
                    width: 300
                }, 800);
            },
            "mouseout": function () {
                $(this).animate({
                    width: 200
                }, 800);
            }
        });
    </script>

    <div id="animacjaTestowa3" class="test-block">Kliknij, abym urósł</div>
    <script>
        $("#animacjaTestowa3").on("click", function () {
            if (!$(this).is(":animated")) {
                $(this).animate({
                    width: "+=" + 50,
                    height: "+=" + 10,
                    opacity: "+=" + 0.1,
                    duration: 3000
                });
            }
        });
    </script>

    <div class="container">
        <?php
        // Sprawdzanie, czy page_id jest ustawione i poprawne
        if (isset($_GET['page_id'])) {
            $page_id = $_GET['page_id'];

            // Walidacja page_id
            if (!filter_var($page_id, FILTER_VALIDATE_INT) || $page_id <= 0) {
                echo "<p>Nieprawidłowe ID strony.</p>";
                exit;
            }

            // Przygotowanie zapytania SQL (z użyciem przygotowanego zapytania)
            $query = "SELECT page_title, page_content FROM page_list WHERE id = ? AND status = 1";
            if ($stmt = mysqli_prepare($link, $query)) {
                mysqli_stmt_bind_param($stmt, 'i', $page_id);  // Wiązanie parametru
                mysqli_stmt_execute($stmt);  // Wykonanie zapytania
                $result = mysqli_stmt_get_result($stmt);  // Pobranie wyników

                // Sprawdzanie wyników zapytania
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        echo "<h1>" . htmlspecialchars($row['page_title']) . "</h1>";
                        echo $row['page_content'];
                    } else {
                        echo "<p>Strona o tym ID nie została znaleziona.</p>";
                    }
                } else {
                    echo "<p>Błąd zapytania: " . mysqli_error($link) . "</p>";
                }
                mysqli_stmt_close($stmt);  // Zamykanie przygotowanego zapytania
            } else {
                echo "<p>Błąd przygotowania zapytania.</p>";
            }
        } else {
            echo "<p>Wybierz stronę z menu.</p>";
        }
        ?>
    </div>
</body>
</html>
