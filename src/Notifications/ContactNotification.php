<?php

namespace App\Notifications;

use App\Entity\ContactEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class ContactNotification
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(ContactEmail $contact_email): void
    {
        $email = (new TemplatedEmail())
            ->from('
informatique@hourraweb.fr')
            ->to('contact@hourraweb.fr')
            ->cc('bruno.bezard@gmail.com')
            ->subject('Demande d\'information - Site Hourraweb informatique')
            ->textTemplate('email/information.txt.twig')
            ->htmlTemplate('email/information.html.twig')
            ->context([
                'message' => $contact_email,
                'date' => new \DateTime('now'),
            ])
        ;

        $this->mailer->send($email);
    }
}
