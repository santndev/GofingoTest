<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Product;
use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductListener
{
    private string $receiveEmailAddress;

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, string $receiveEmailAddress)
    {
        $this->bus                 = $bus;
        $this->receiveEmailAddress = $receiveEmailAddress;
    }

    public function postPersist(Product $product): void
    {
        $this->bus->dispatch(new SendEmailMessage(
            [$this->receiveEmailAddress],
            "Product added",
            "Product Title: {$product->getTitle()}"
        ));
    }

    public function postUpdate(Product $product): void
    {
        $this->bus->dispatch(new SendEmailMessage(
            [$this->receiveEmailAddress],
            "Product updated",
            "Product Title: {$product->getTitle()}"
        ));
    }
}
