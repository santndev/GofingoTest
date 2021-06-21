<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Service\MailService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendEmailMessageHandler implements MessageHandlerInterface
{
    /**
     * @var MailService
     */
    private MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function __invoke(SendEmailMessage $message)
    {
        try {
            $this->mailService->sendEmail(
                $message->getReceiveEmails(),
                $message->getTitle(),
                $message->getContent()
            );
        } catch (\Exception $e) {
            // TODO: store for maintain: $e
            // need logging errors
            throw new \Exception("Email cannot be send");
        }
    }
}
