<?php

namespace App\Entity;

class Card
{
    /** @var string|null $number */
    private $number;

    /** @var string|null $cvv */
    private $cvv;

    /** @var string|null $holder */
    private $holder;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): void
    {
        $this->cvv = $cvv;
    }

    public function getHolder(): ?string
    {
        return $this->holder;
    }

    public function setHolder(string $holder): void
    {
        $this->holder = $holder;
    }
}