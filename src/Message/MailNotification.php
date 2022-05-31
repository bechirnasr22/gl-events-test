<?php

namespace App\Message;

class MailNotification
{
    private $description;
    private $to;

    public function __construct(string $description, string $to)
    {
        $this->description = $description;
        $this->to = $to;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
