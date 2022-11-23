<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher
{
    const STATUS_NOT_APPLIED = 1;
    const STATUS_APPLIED = 2;
    const PAGINATION_LIMIT = 2;
    const STATUS_PERCENTAGE_TYPE = 1;
    const STATUS_FIXED_TYPE = 2;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $discount_amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expires_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified_date;

    /**
     * @ORM\OneToOne(targetEntity=OrderVoucher::class, mappedBy="voucher_id", orphanRemoval=true)
     */
    private $orderVoucher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discount_amount;
    }

    public function setDiscountAmount(int $discount_amount): self
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expires_at;
    }

    public function setExpiresAt(\DateTimeInterface $expires_at): self
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modified_date;
    }

    public function setModifiedDate(\DateTimeInterface $modified_date): self
    {
        $this->modified_date = $modified_date;

        return $this;
    }
}
