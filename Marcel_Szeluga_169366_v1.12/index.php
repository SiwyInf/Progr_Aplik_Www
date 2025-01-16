<?php
error_reporting(E_ALL);
include('admin/cfg.php');  // Dołączenie pliku konfiguracyjnego z połączeniem do bazy danych

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
    <title>Moje hobby to</title>

    <link rel="stylesheet" type="text/css" href="css/style_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Dodanie skryptu kolorujtlo.js -->
    <script src="js/kolorujtlo.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            padding-left: 220px;
        }

        nav {
            width: 200px;
            height: 100vh;
            background-color: #0073e6;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            margin: 10px 0;
        }

        nav ul li a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #005bb5;
            border-radius: 5px;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #zegarek {
            font-size: 24px;
            font-weight: bold;
            color: #0073e6;
            text-align: center;
            margin: 20px 0;
        }

        .test-block {
            width: 200px;
            height: 50px;
            background-color: #0073e6;
            color: white;
            text-align: center;
            line-height: 50px;
            margin: 20px auto;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .test-block:hover {
            background-color: #005bb5;
        }

        .color-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #0073e6;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .color-button:hover {
            background-color: #005bb5;
        }
    </style>
</head>

<body>
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

    <!-- Zegar -->
    <div id="zegarek">00:00:00</div>

    <script>
        function aktualizujZegar() {
            let teraz = new Date();
            let godzina = teraz.getHours().toString().padStart(2, '0');
            let minuta = teraz.getMinutes().toString().padStart(2, '0');
            let sekunda = teraz.getSeconds().toString().padStart(2, '0');
            document.getElementById("zegarek").innerText = `${godzina}:${minuta}:${sekunda}`;
        }

        setInterval(aktualizujZegar, 1000);
        aktualizujZegar();
    </script>

    <!-- Animacje -->
<div id="animacjaTestowa1" class="test-block">Kliknij, a się powiększę</div>
<script>
    $("#animacjaTestowa1").on("click", function () {
        $(this).animate({
            width: "500px",
            opacity: 0.4,
            fontSize: "1.5em",
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
                width: "+=50",
                height: "+=10",
                opacity: "+=0.1"
            }, 1000);
        }
    });
</script>

<!-- Przycisk do zmiany koloru tła -->
<div class="color-buttons">
    <button class="color-button" onclick="changeBackground('#ff5733')">Zmień tło na pomarańczowy</button>
    <button class="color-button" onclick="changeBackground('#33ff57')">Zmień tło na zielony</button>
    <button class="color-button" onclick="changeBackground('#00FFFF')">Zmień tło na niebieski</button>
    <button class="color-button" onclick="changeBackground('#FF00FF')">Zmień tło na różowy</button>
</div>

    <div class="container">
        <?php
        if (isset($_GET['page_id'])) {
            $page_id = $_GET['page_id'];

            if (!filter_var($page_id, FILTER_VALIDATE_INT) || $page_id <= 0) {
                echo "<p>Nieprawidłowe ID strony.</p>";
                exit;
            }

            $query = "SELECT page_title, page_content FROM page_list WHERE id = ? AND status = 1";
            if ($stmt = mysqli_prepare($link, $query)) {
                mysqli_stmt_bind_param($stmt, 'i', $page_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        echo "<h1>" . htmlspecialchars($row['page_title']) . "</h1>";
                        echo $row['page_content'];
                    } else {
                        echo "<p>Strona o tym ID nie została znaleziona.</p>";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            echo "<p>Wybierz stronę z menu.</p>";
        }
        ?>
    </div>
</body>
</html>
