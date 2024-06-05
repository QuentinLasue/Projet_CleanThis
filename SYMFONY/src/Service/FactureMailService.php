<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Twig\Environment;

class FactureMailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendFactureMail(string $recipientEmail, string $pdfContent): void
    {
        $fileName = 'client.pdf';


        $email = (new Email())
            ->from('cleanthis154@gmail.com')
            ->to($recipientEmail)
            ->subject('Facture Cleanthis')
            ->text('Voici votre facture. Merci pour votre confiance.')
            ->attach($pdfContent,$fileName, 'application/pdf');

        $this->mailer->send($email);
    }
}
