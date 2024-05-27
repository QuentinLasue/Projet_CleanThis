<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PasswordMailer
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendPasswordEmail(string $recipientEmailAddress, string $generatedPassword): bool
    {
        $this->logger->info('Attempting to send password email to: ' . $recipientEmailAddress);

        $email = (new Email())
            ->from('cleanthis154@gmail.com')
            ->to($recipientEmailAddress)
            ->subject('Your New Password')
            ->text(
                "Dear user,\n\n" .
                "Your new password is: {$generatedPassword}\n\n" .
                "Please use this password to login to your account.\n\n" .
                "For security reasons, we recommend changing your password after logging in."
            );

        try {
            $this->mailer->send($email);
            $this->logger->info('Password email sent successfully to: ' . $recipientEmailAddress);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Failed to send password email to: ' . $recipientEmailAddress);
            $this->logger->error($e->getMessage());
            return false;
        }
    }
}

