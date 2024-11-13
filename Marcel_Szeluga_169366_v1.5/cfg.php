<?php
// Plik cfg.php: Ustawienia połączenia z bazą danych
try {
    $pdo = new PDO("mysql:host=localhost;dbname=moja_strona", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Włączenie trybu obsługi błędów
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>
