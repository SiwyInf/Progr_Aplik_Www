<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOSZYK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        td {
            background-color: #f9f9f9;
        }

        form {
            display: block;
            width: 100%;
            margin-top: 30px;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .actions a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .total {
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Twoje treści strony -->
    </div>
</body>
</html>
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
