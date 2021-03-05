<?php

namespace App\Message;

final class SendTestEmailJob
{
    /**
     * @var string
     */
    private $sendTo;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $message;

    public function __construct(string $sendTo, string $subject, string $message)
     {
         $this->sendTo = $sendTo;
         $this->subject = $subject;
         $this->message = $message;
     }

    /**
     * @return string
     */
    public function getSendTo(): string
    {
        return $this->sendTo;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
