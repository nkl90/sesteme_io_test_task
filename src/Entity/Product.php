<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Brick\Money\Money;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $price;
    #[ORM\Column(type: Types::STRING, length: 3)]
    private string $priceCurrency;

    public function __construct()
    {
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function changeName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): Money
    {
        return Money::ofMinor($this->price, $this->priceCurrency);
    }

    public function changePrice(Money $price): void
    {
        $this->price = $price->getMinorAmount()->toInt();
        $this->priceCurrency = $price->getCurrency()->getCurrencyCode();
    }
}
