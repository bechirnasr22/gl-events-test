<?php

namespace App\MessageHandler;

use App\Message\MailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class MailNotificationHandler implements MessageHandlerInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(MailNotification $message)
    {
        $email = (new Email())
            ->from("contact@bechir.info")
            ->to($message->getTo())
            ->subject('Newsletter GL Events')
            ->text($message->getDescription());
        $this->mailer->send($email);
    }
}
