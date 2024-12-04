<?php
// Dane do połączenia z bazą danych
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'moja_strona');

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Sprawdzenie połączenia
if (!$link) {
    echo '<b>Przerwane połączenie: </b>' . mysqli_connect_error();
    exit();  // Zakończenie dalszego przetwarzania kodu
}

// Ustawienia administratora
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASS_HASH', password_hash('1234', PASSWORD_DEFAULT)); // Hasło haszowane

// Przykład sprawdzenia hasła podczas logowania
// $userInputPassword = '1234'; // Hasło wprowadzone przez użytkownika
// if (password_verify($userInputPassword, ADMIN_PASS_HASH)) {
//     echo 'Hasło poprawne!';
// } else {
//     echo 'Hasło niepoprawne!';
// }

?>
