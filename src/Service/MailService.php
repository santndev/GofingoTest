<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param array       $to
     * @param string      $subject
     * @param string      $text
     * @param string|null $html
     * @param array|null  $cc
     * @param array|null  $bcc
     * @param array|null  $replyTo
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        array $to,
        string $subject,
        string $text,
        string $html = null,
        ?array $cc = null,
        ?array $bcc = null,
        ?array $replyTo = null
    ): void {
        $email = (new Email())
            ->from(new Address($_ENV['MAILER_SENDER'], $_ENV['MAILER_SENDER_REALNAME']))
            ->to(...$to)
            ->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($text);

        if ($cc) {
            $email->cc(...$cc);
        }
        if ($bcc) {
            $email->bcc(...$bcc);
        }
        if ($replyTo) {
            $email->replyTo(...$replyTo);
        }
        if ($html) {
            $email->html($html);
        }

        $this->mailer->send($email);
    }
}
