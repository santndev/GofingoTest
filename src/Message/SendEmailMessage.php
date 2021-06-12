<?php

namespace App\Message;

final class SendEmailMessage
{
    /**
     * @var array
     */
    private array $receiveEmails;
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string
     */
    private string $content;

    public function __construct(array $receiveEmails, string $title, string $content)
    {
        $this->receiveEmails = $receiveEmails;
        $this->title        = $title;
        $this->content       = $content;
    }

    /**
     * @return array
     */
    public function getReceiveEmails(): array
    {
        return $this->receiveEmails;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
