<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contact {
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

    public function WyslijMailKontakt() {
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo "Proszę wypełnić wszystkie pola!";
            $this->PokazKontakt();
        } else {
            $temat = htmlspecialchars($_POST['temat']);
            $tresc = htmlspecialchars($_POST['tresc']);
            $email = htmlspecialchars($_POST['email']);

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.onet.pl';
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;

                // Zmienna środowiskowa
                $mail->Username = getenv('SMTP_USERNAME'); 
                $mail->Password = getenv('SMTP_PASSWORD'); 

                $mail->setFrom('siwy.chill@onet.pl', 'Formularz Kontaktowy');
                $mail->addAddress('odbiorca@adres.com');

                $mail->isHTML(false);
                $mail->Subject = $temat;
                $mail->Body = "Wiadomość od: $email\n\n$tresc";

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

    public function PrzypomnijHaslo() {
        if (empty($_POST['email'])) {
            echo "Proszę podać swój email.";
        } else {
            $email = htmlspecialchars($_POST['email']);
            $resetLink = "https://example.com/reset-haslo?token=" . bin2hex(random_bytes(16));

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.onet.pl';
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;

                $mail->Username = getenv('SMTP_USERNAME'); 
                $mail->Password = getenv('SMTP_PASSWORD');

                $mail->setFrom('siwy.chill@onet.pl', 'Formularz Kontaktowy');
                $mail->addAddress('admin@adres.com');

                $mail->isHTML(false);
                $mail->Subject = "Przypomnienie hasła";
                $mail->Body = "Użytkownik o adresie e-mail $email zażądał przypomnienia hasła. Kliknij poniższy link, aby zresetować swoje hasło:\n\n$resetLink";

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
