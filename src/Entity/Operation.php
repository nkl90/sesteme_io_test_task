<?php

namespace App\Entity;

use App\Enum\PaymentStatusEnum;
use App\Enum\TaxByCountryEnum;
use App\Repository\OperationRepository;
use Brick\Money\Money;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    private UuidInterface|string $id;

    #[ORM\Column(length: 255, enumType: PaymentStatusEnum::class)]
    private PaymentStatusEnum $status;

    #[ORM\Column(type: Types::INTEGER)]
    private int $price;

    #[ORM\Column(length: 3)]
    private string $priceCurrency;

    #[ORM\Column(type: Types::INTEGER)]
    private int $tax;

    #[ORM\Column(length: 3)]
    private string $taxCurrency;

    #[ORM\Column(length: 255)]
    private string $taxNumber;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeInterface $createdAt;

    public function __construct(
        string $taxNumber,
        Money $price
    )
    {
        $this->setTax($this->calculateTax($price, $taxNumber));
        $this->setPrice($price);
        $this->setTaxNumber($taxNumber);
        $this->id = Uuid::uuid4();
        $this->status = PaymentStatusEnum::NEW;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): PaymentStatusEnum
    {
        return $this->status;
    }

    public function checkout(): self
    {
        $this->status = PaymentStatusEnum::PAID;

        return $this;
    }

    public function getPrice(): Money
    {
        return Money::ofMinor($this->price, $this->priceCurrency);
    }

    private function setPrice(Money $price): self
    {
        $this->price = $price->getMinorAmount()->toInt();
        $this->priceCurrency = $price->getCurrency()->getCurrencyCode();

        return $this;
    }


    public function getTax(): Money
    {
        return Money::ofMinor($this->tax, $this->taxCurrency);
    }

    private function setTax(Money $tax): self
    {
        $this->tax = $tax->getMinorAmount()->toInt();
        $this->taxCurrency = $tax->getCurrency()->getCurrencyCode();

        return $this;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    private function setTaxNumber(string $taxNumber): self
    {
        Assert::regex(
            $taxNumber,
            TaxByCountryEnum::getRegex(),
            'Invalid tax number'
        );
        $this->taxNumber = $taxNumber;

        return $this;
    }

    private function parseTaxNumber(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }

    private function calculateTax(Money $price, string $taxNumber): Money
    {
        $countryCode = $this->parseTaxNumber($taxNumber);
        $tax = $price->getMinorAmount()->toInt() * TaxByCountryEnum::taxByCountry($countryCode);
        return Money::ofMinor($tax, $price->getCurrency()->getCurrencyCode());
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getTotal(): Money
    {
        return $this->getPrice()->plus($this->getTax());
    }
}
