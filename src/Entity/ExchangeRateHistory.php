<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRateHistoryRepository")
 */
class ExchangeRateHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="exchangeRateHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $ask_price;

    /**
     * @ORM\Column(type="float")
     */
    private $bid_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAskPrice(): ?float
    {
        return $this->ask_price;
    }

    public function setAskPrice(float $ask_price): self
    {
        $this->ask_price = $ask_price;

        return $this;
    }

    public function getBidPrice(): ?float
    {
        return $this->bid_price;
    }

    public function setBidPrice(float $bid_price): self
    {
        $this->bid_price = $bid_price;

        return $this;
    }
}
