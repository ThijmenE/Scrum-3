<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Controller voor het contactformulier: validatie, verzending en bevestiging
class ContactController extends Controller
{
    // index(): toont het lege contactformulier zonder vooringevulde gegevens
    public function index(): void
    {
        $this->render('contact', ['errors' => [], 'post' => []]);
    }

    // send(): valideert het formulier, bouwt e-mailteksten op en verstuurt twee mails
    public function send(): void
    {
        $errors = [];

        // Lees en zuiver alle POST-velden uit
        $post   = [
            'inquiry_type' => trim($_POST['inquiry_type'] ?? ''),
            'name'         => trim($_POST['name'] ?? ''),
            'email'        => trim($_POST['email'] ?? ''),
            'company'      => trim($_POST['company'] ?? ''),
            'project'      => trim($_POST['project'] ?? ''),
            'timeline'     => trim($_POST['timeline'] ?? ''),
            'budget'       => trim($_POST['budget'] ?? ''),
        ];

        // Valideer de verplichte velden
        if (empty($post['inquiry_type'])) $errors[] = 'Please select an inquiry type.';
        if (empty($post['name']))         $errors[] = 'Name is required.';
        if (empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }
        if (empty($post['project']))      $errors[] = 'Please describe your project or request.';

        // Toon fouten opnieuw in het formulier als de validatie mislukt
        if (!empty($errors)) {
            $this->render('contact', ['errors' => $errors, 'post' => $post]);
            return;
        }

        $safeEmail   = filter_var($post['email'], FILTER_SANITIZE_EMAIL);
        $studioEmail = $_ENV['MAIL_TO'] ?? 'nickeyloots0161@gmail.com';

        // Bouw de notificatie-e-mail op voor het studio-adres
        $notifBody  = "New inquiry received via the website.\n\n";
        $notifBody .= "Type:    {$post['inquiry_type']}\n";
        $notifBody .= "Name:    {$post['name']}\n";
        $notifBody .= "Email:   {$safeEmail}\n";
        if ($post['company'])  $notifBody .= "Company: {$post['company']}\n";
        $notifBody .= "\nProject / Request:\n{$post['project']}\n";
        if ($post['timeline']) $notifBody .= "\nTimeline: {$post['timeline']}";
        if ($post['budget'])   $notifBody .= "\nBudget:   {$post['budget']}";

        // Bouw de bevestigings-e-mail op voor de afzender
        $replyBody = "Hi {$post['name']},\n\n"
            . "Thank you for reaching out to me and sharing your ideas or project.\n"
            . "I've received your inquiry and will take the time to review it carefully. "
            . "I aim to respond as soon as possible.\n\n"
            . "Warm regards,\n\nGrytsje Suze";

        // Verstuur beide mails en stuur door bij succes, toon foutmelding bij mislukking
        try {
            $this->sendMail($studioEmail, 'Grytsje Suze Studio', "New Inquiry: {$post['inquiry_type']} – from {$post['name']}", $notifBody);
            $this->sendMail($safeEmail, $post['name'], 'Thank you for your message', $replyBody);
            $this->redirect('/contact/bedankt');
        } catch (Exception $e) {
            $errors[] = 'Could not send your message. Please try again later.';
            $this->render('contact', ['errors' => $errors, 'post' => $post]);
        }
    }

    // bedankt(): toont de bedankpagina na een succesvolle formulierverzending
    public function bedankt(): void
    {
        $this->render('contact-bedankt');
    }

    // sendMail(): privé-hulpfunctie die een e-mail verstuurt via PHPMailer en SMTP
    private function sendMail(string $toAddress, string $toName, string $subject, string $body): void
    {
        // Stel de SMTP-verbinding in op basis van omgevingsvariabelen
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? 587);
        $mail->CharSet    = 'UTF-8';

        // Stel afzender, ontvanger, onderwerp en berichttekst in
        $mail->setFrom($_ENV['MAIL_USERNAME'] ?? '', $_ENV['MAIL_FROM_NAME'] ?? 'Grytsje Suze');
        $mail->addAddress($toAddress, $toName);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->isHTML(false);
        $mail->send();
    }
}
