<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
/* po tym komentarzu będzie kod do dynamicznego ładowania stron */
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
            <li><a href="index.php?page=Menu">Menu</a></li>
            <li><a href="index.php?page=Kontakt">Kontakt</a></li>
            <li><a href="index.php?page=Wędki">Wędki</a></li>
            <li><a href="index.php?page=Kołowrotki">Kołowrotki</a></li>
            <li><a href="index.php?page=Plecionki">Plecionki</a></li>
            <li><a href="index.php?page=Przynęty">Przynęty</a></li>
            <li><a href="index.php?page=Rekordy">Rekordy</a></li>
			<li><a href="index.php?page=Filmy">Filmy</a></li>
			
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
// Sprawdź, czy zmienna $_GET['page'] jest ustawiona i dołącz odpowiednią treść
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Zabezpiecz przed wstrzyknięciami kodu
    $allowed_pages = ['Menu', 'Kontakt', 'Wędki', 'Kołowrotki', 'Plecionki', 'Przynęty', 'Rekordy', 'Filmy'];

    if (in_array($page, $allowed_pages)) {
        // Konstrukcja pełnej ścieżki do pliku
        $file = "pages/$page.php";
        if (file_exists($file)) {
            include($file); // Załaduj stronę
        } else {
            echo "<p>Strona $page nie została znaleziona.</p>";
        }
    } else {
        echo "<p>Podana strona nie istnieje.</p>";
    }
} else {
    include("pages/Menu.php"); // Domyślna strona główna
}
?>
    </div>
</body>

</html>
