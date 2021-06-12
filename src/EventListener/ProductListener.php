<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductListener
{
    /**
     * @var string
     */
    private string $receiveEmailAddress;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;


    public function __construct(MessageBusInterface $bus, string $receiveEmailAddress)
    {
        $this->bus                 = $bus;
        $this->receiveEmailAddress = $receiveEmailAddress;
    }

    /**
     * @param Product $product
     */
    public function postPersist(Product $product): void
    {
        $this->bus->dispatch(new SendEmailMessage([$this->receiveEmailAddress],
            "Product added",
            "Product Title: {$product->getTitle()}"
        ));
    }

    /**
     * @param Product $product
     */
    public function postUpdate(Product $product): void
    {
        $this->bus->dispatch(new SendEmailMessage([$this->receiveEmailAddress],
            "Product updated",
            "Product Title: {$product->getTitle()}"
        ));
    }
}
