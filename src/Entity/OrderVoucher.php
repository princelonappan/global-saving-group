<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderVoucherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrderVoucherRepository::class)
 */
class OrderVoucher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer_id;

    /**
     * @ORM\OneToOne(targetEntity=Voucher::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $voucher_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(Order $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customer_id;
    }

    public function setCustomerId(Customer $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getVoucherId(): ?Voucher
    {
        return $this->voucher_id;
    }

    public function setVoucherId(Voucher $voucher_id): self
    {
        $this->voucher_id = $voucher_id;

        return $this;
    }
}
