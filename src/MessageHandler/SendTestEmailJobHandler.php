<?php

namespace App\MessageHandler;

use App\Message\SendTestEmailJob;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

final class SendTestEmailJobHandler implements MessageHandlerInterface
{
    private const FROM = 'mgorski.dev@gmail.com';

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param SendTestEmailJob $message
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendTestEmailJob $message)
    {
        $email = (new Email())
            ->from(self::FROM)
            ->to($message->getSendTo())
            ->subject($message->getSubject())
            ->text($message->getMessage());

        $this->mailer->send($email);
    }
}
