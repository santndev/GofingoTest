<?php

declare(strict_types=1);

namespace App\Message;

final class SendEmailMessage
{
    private array $receiveEmails;

    private string $title;

    private string $content;

    public function __construct(array $receiveEmails, string $title, string $content)
    {
        $this->receiveEmails = $receiveEmails;
        $this->title        = $title;
        $this->content       = $content;
    }

    public function getReceiveEmails(): array
    {
        return $this->receiveEmails;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
