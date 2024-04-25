<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendMail(string $from, string $subject, string $template, array $context, string $to)
    {
        $htmlContent = $this->twig->render($template, $context);

        $email = (new Email())
            ->from($from)
            ->subject($subject)
            ->to($to)
            ->html($htmlContent); // Utilisation de la méthode html() pour définir le contenu HTML de l'email

        $this->mailer->send($email);
    }
}
