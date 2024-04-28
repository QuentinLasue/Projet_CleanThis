<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    private string $adminEmail;
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(string $adminEmail, MailerInterface $mailer, Environment $twig)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendWelcomeMail(): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->subject('Welcome')
            ->text('Welcome to our website!');

        $this->mailer->send($email);
    }
}

    