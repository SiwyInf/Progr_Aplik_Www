<?php
$nr_indeksu = '169366';
$nrGrupy = '4';
echo 'Marcel Szeluga ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';
echo 'Zastosowanie metody include() <br />';

// a) Metoda include(), require_once()
echo 'a) Metoda include(), require_once():<br />';
if (file_exists('plik_z_include.php')) {
    include 'plik_z_include.php';
} else {
    echo "Plik include.php nie istnieje.<br />";
}

if (file_exists('plik_z_require_once.php')) {
    require_once 'plik_z_require_once.php';
} else {
    echo "Plik require_once.php nie istnieje.<br />";
}

echo '<br />';

// b) Warunki if, else, elseif, switch
echo 'b) Warunki if, else, elseif, switch:<br />';
$zmienna = 5;

if ($zmienna > 10) {
    echo 'Zmienna jest większa niż 10.<br />';
} elseif ($zmienna == 10) {
    echo 'Zmienna jest równa 10.<br />';
} else {
    echo 'Zmienna jest mniejsza niż 10.<br />';
}

switch ($zmienna) {
    case 5:
        echo 'Zmienna ma wartość 5.<br />';
        break;
    default:
        echo 'Zmienna ma inną wartość.<br />';
}
echo '<br />';

// c) Pętla while() i for()
echo 'c) Pętla while() i for():<br />';

// Pętla while
$i = 0;
while ($i < 5) {
    echo 'Pętla while: Iteracja ' . $i . '<br />';
    $i++;
}

// Pętla for
for ($j = 0; $j < 5; $j++) {
    echo 'Pętla for: Iteracja ' . $j . '<br />';
}
echo '<br />';

// d) Typy zmiennych $_GET, $_POST, $_SESSION
echo 'd) Typy zmiennych $_GET, $_POST, $_SESSION:<br />';

// Przykład użycia $_GET
echo 'Przykład użycia $_GET:<br />';
if (isset($_GET['param'])) {
    echo 'Wartość parametru GET: ' . htmlspecialchars($_GET['param'], ENT_QUOTES, 'UTF-8') . '<br />';
}

// Przykład użycia $_POST
echo 'Przykład użycia $_POST:<br />';
if (isset($_POST['param'])) {
    echo 'Wartość parametru POST: ' . htmlspecialchars($_POST['param'], ENT_QUOTES, 'UTF-8') . '<br />';
}

// Rozpoczęcie sesji i użycie $_SESSION
session_start();
$_SESSION['zmienna'] = 'Wartość zmiennej sesyjnej';
echo 'Wartość zmiennej sesyjnej: ' . $_SESSION['zmienna'] . '<br />';
?>
