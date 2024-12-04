<?php
// Konfiguracja połączenia z bazą danych
function getPDOConnection() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=moja_strona", "root", "");  // Zaktualizuj dane dostępowe
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Włącz tryb obsługi błędów
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Ustawienie trybu pobierania wyników
        return $pdo;
    } catch (PDOException $e) {
        // Obsługa błędów połączenia
        error_log("Błąd połączenia: " . $e->getMessage()); // Zapisz błąd w logach
        die("Wystąpił problem z połączeniem z bazą danych. Proszę spróbować później.");
    }
}

function PokazPodstrone($id) {
    if (!is_int($id)) {
        return ['blad' => 'Nieprawidłowe ID strony.'];
    }

    // Połączenie z bazą danych
    $pdo = getPDOConnection();

    // Przygotowanie zapytania (zabezpieczenie przed SQL Injection)
    $stmt = $pdo->prepare("SELECT page_title, page_content FROM page_list WHERE id = :id AND status = 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Walidacja danych wejściowych
    $stmt->execute();

    // Pobranie wyniku zapytania
    $row = $stmt->fetch();

    if (!$row) {
        return ['blad' => 'Strona nie została znaleziona.'];
    } else {
        return [
            'title' => htmlspecialchars($row['page_title']),
            'content' => nl2br(htmlspecialchars($row['page_content']))
        ];
    }
}

// Pobieranie ID strony z parametrów GET
if (isset($_GET['page_id']) && filter_var($_GET['page_id'], FILTER_VALIDATE_INT) && $_GET['page_id'] > 0) {
    $id = (int) $_GET['page_id']; // Pobranie i rzutowanie na int
    $pageData = PokazPodstrone($id);

    if (isset($pageData['blad'])) {
        echo "<p>" . $pageData['blad'] . "</p>";
    } else {
        echo "<h1>" . $pageData['title'] . "</h1>";
        echo "<p>" . $pageData['content'] . "</p>";
    }
} else {
    echo "<p>Brak ID strony lub nieprawidłowe ID.</p>";
}
?>
