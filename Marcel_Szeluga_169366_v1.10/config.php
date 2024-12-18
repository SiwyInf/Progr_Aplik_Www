<?php
$mysqli = new mysqli("localhost", "root", "", "moja_strona");

if ($mysqli->connect_error) {
    die("Błąd połączenia z bazą danych: " . $mysqli->connect_error);
}
?>
