<?php
// Plik: pages/Kontakt.php
?>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <title>Kontakt</title>
    <link rel="stylesheet" type="text/css" href="css/style_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
</head>
<h1 class="kon">Kontakt</h1>


<div class="container">
    <form action="Wyslij.php" method="POST">
        <label for="name">Imię i nazwisko:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">Temat:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Wiadomość:</label>
        <textarea id="message" name="message" rows="4" required></textarea>

        <input type="submit" value="Wyślij">
    </form>
</div>

</body>
</html>
