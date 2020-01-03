<?php

namespace App\Entity;

class Payment
{
    /** @var Card $card */
    private $card;

    /**
     * @var int|null $amount
     * Assert\NotBlank
     */
    private $amount;

    /**
     * @var string|null $currency
     * Assert\NotBlank
     */
    private $currency;

    public function __construct()
    {
        $this->card = new Card();
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}