<?php
session_start();

// Funkcja obsługująca płatność
function processPayment() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sprawdź, czy wszystkie dane są dostępne
        if (isset($_POST['total']) && isset($_POST['payment_method'])) {
            $total = floatval($_POST['total']);
            $paymentMethod = htmlspecialchars($_POST['payment_method']);

            // Sprawdzenie poprawności danych
            if ($total > 0) {
                echo "<h1>Płatność</h1>";
                echo "<p>Wybrano metodę płatności: <strong>{$paymentMethod}</strong></p>";
                echo "<p>Kwota do zapłaty: <strong>{$total} zł</strong></p>";
                
                // Tutaj można dodać logikę integracji z bramką płatności, np. PayPal, BLIK itd.
                echo "<p>Płatność została pomyślnie przetworzona. Dziękujemy za zakupy!</p>";

                // Opróżnij koszyk po pomyślnej płatności
                unset($_SESSION['cart']);
            } else {
                echo "<p>Błąd: Kwota do zapłaty jest nieprawidłowa.</p>";
            }
        } else {
            echo "<p>Błąd: Brak wymaganych danych.</p>";
        }
    } else {
        echo "<p>Błąd: Nieprawidłowa metoda żądania.</p>";
    }
}

// Wywołanie funkcji przetwarzającej płatność
processPayment();
?>
