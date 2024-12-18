<?php
// Plik: pages/Dziekujemy.php
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="pl">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dziękujemy! Wiadomość została wysłana</title>
    <link rel="stylesheet" type="text/css" href="css/style_css.css">
</head>
<body>
    <div class="container">
        <img src="../img/ryba.jpg" alt="Ryba" class="thank-you-image">
    </div>

    <header>
        <h1 class="napis">Wiadomość została wysłana</h1>
    </header>

    <section class="message-container">
        <p class="message-text">Dziękujemy za Twoją wiadomość. Zajmiemy się nią jak najszybciej. Wróć na stronę główną, aby kontynuować.</p>
        <button class="back-button" onclick="window.location.href='index.php'">Powrót na stronę główną</button>
    </section>
</body>
</html>
