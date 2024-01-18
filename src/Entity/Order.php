<?php

namespace App\Entity;

use App\Entity\Trait\DatesTrait;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\EntityListeners(['App\EntityListener\OrderListener'])]
class Order
{

    public const PENDING = 'PENDING';
    public const PAID = 'PAID';
    public const FAILED = 'FAILED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $purchaser = null;

    #[ORM\Column(length: 20)]
    private ?string $status = self::PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StripeReference = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $PaymentUrl = null;

    use DatesTrait;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getPurchaser(): ?User
    {
        return $this->purchaser;
    }

    public function setPurchaser(?User $purchaser): static
    {
        $this->purchaser = $purchaser;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStripeReference(): ?string
    {
        return $this->StripeReference;
    }

    public function setStripeReference(string $StripeReference): static
    {
        $this->StripeReference = $StripeReference;

        return $this;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->PaymentUrl;
    }

    public function setPaymentUrl(?string $PaymentUrl): static
    {
        $this->PaymentUrl = $PaymentUrl;

        return $this;
    }
}
