<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';

// Załaduj bibliotekę PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contact {
    // Metoda generująca formularz kontaktowy
    public function PokazKontakt() {
        echo '<form method="POST" action="contact.php">
                <label for="temat">Temat:</label><br>
                <input type="text" id="temat" name="temat" required><br><br>
                
                <label for="tresc">Treść wiadomości:</label><br>
                <textarea id="tresc" name="tresc" rows="5" required></textarea><br><br>
                
                <label for="email">Twój email:</label><br>
                <input type="email" id="email" name="email" required><br><br>
                
                <button type="submit" name="sendContact">Wyślij</button>
            </form>';
    }

    // Metoda obsługująca wysyłanie maila
    public function WyslijMailKontakt() {
        // Sprawdzenie, czy pola formularza są wypełnione
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo "Proszę wypełnić wszystkie pola!";
            $this->PokazKontakt(); // Ponowne wywołanie formularza w razie braków
        } else {
            // Oczyszczenie danych wejściowych
            $temat = htmlspecialchars($_POST['temat']);
            $tresc = htmlspecialchars($_POST['tresc']);
            $email = htmlspecialchars($_POST['email']);

            // Tworzenie obiektu PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Ustawienia SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.onet.pl';  // Używamy serwera SMTP Onet
                $mail->Port = 465;  // Port 465 dla SSL
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL
                $mail->SMTPAuth = true;

                // Uwierzytelnianie SMTP
                $mail->Username = 'siwy.chill@onet.pl';  // Twój adres e-mail Onet
                $mail->Password = 'Twoje_hasło';  // Hasło do konta Onet

                // Nadawca i odbiorca
                $mail->setFrom('siwy.chill@onet.pl', 'Formularz Kontaktowy');
                $mail->addAddress('odbiorca@adres.com');  // Zmień na adres odbiorcy

                // Treść wiadomości
                $mail->isHTML(false);  // Ustawienie wiadomości jako tekstowej
                $mail->Subject = $temat;
                $mail->Body    = "Wiadomość od: $email\n\n$tresc";

                // Wysyłanie maila
                if ($mail->send()) {
                    echo "Wiadomość została wysłana pomyślnie!";
                } else {
                    echo "Wystąpił błąd podczas wysyłania wiadomości.";
                }
            } catch (Exception $e) {
                echo "Wystąpił błąd podczas wysyłania wiadomości: {$mail->ErrorInfo}";
            }
        }
    }

    // Metoda przypomnienia hasła
    public function PrzypomnijHaslo() {
        // Sprawdzanie, czy użytkownik podał email
        if (empty($_POST['email'])) {
            echo "Proszę podać swój email.";
        } else {
            $email = htmlspecialchars($_POST['email']);

            // Tworzymy unikalny link do resetowania hasła (może być to np. token)
            $resetLink = "https://example.com/reset-haslo?token=" . md5($email . time());

            // Tworzenie obiektu PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Ustawienia SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.onet.pl';  // Używamy serwera SMTP Onet
                $mail->Port = 465;  // Port 465 dla SSL
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL
                $mail->SMTPAuth = true;

                // Uwierzytelnianie SMTP
                $mail->Username = 'siwy.chill@onet.pl';  // Twój adres e-mail Onet
                $mail->Password = 'Twoje_hasło';  // Hasło do konta Onet

                // Nadawca i odbiorca (admina)
                $mail->setFrom('siwy.chill@onet.pl', 'Formularz Kontaktowy');
                $mail->addAddress('admin@adres.com');  // Zmień na adres admina

                // Treść wiadomości (link do resetowania hasła)
                $mail->isHTML(false);  // Ustawienie wiadomości jako tekstowej
                $mail->Subject = "Przypomnienie hasła";
                $mail->Body    = "Użytkownik o adresie e-mail $email zażądał przypomnienia hasła. Kliknij poniższy link, aby zresetować swoje hasło:\n\n$resetLink";

                // Wysyłanie maila
                if ($mail->send()) {
                    echo "Link do resetowania hasła został wysłany do admina.";
                } else {
                    echo "Wystąpił błąd podczas wysyłania wiadomości.";
                }
            } catch (Exception $e) {
                echo "Wystąpił błąd podczas wysyłania wiadomości: {$mail->ErrorInfo}";
            }
        }
    }
}

// Obsługa żądań
$contact = new Contact();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sendContact'])) {
        $contact->WyslijMailKontakt();
    } elseif (isset($_POST['sendReset'])) {
        $contact->PrzypomnijHaslo();
    }
} else {
    $contact->PokazKontakt();
}
?>

