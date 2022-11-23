<?php

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\Order;
use App\Entity\OrderVoucher;
use App\Entity\Voucher;
use App\Repository\InvitationRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderVoucherRepository;
use App\Repository\UserRepository;
use App\Repository\VoucherRepository;
use App\Service\CommonService;

class OrderVoucherService
{
    private $orderVoucherRepository;
    private $commonService;

    public function __construct(OrderVoucherRepository $orderVoucherRepository,  CommonService $commonService)
    {
        $this->orderVoucherRepository = $orderVoucherRepository;
        $this->commonService = $commonService;
    }

    public function saveOrder($orderInfo, $customer, $voucher): OrderVoucher
    {
        $order = new OrderVoucher();
        $order->setOrderId($orderInfo);
        $order->setVoucherId($voucher);
        $order->setCustomerId($customer);
        return $this->orderVoucherRepository->save($order);
    }

    public function updateVoucher($voucher, $parameters): Voucher
    {
        $voucher->setDescription($parameters['description']);
        $voucher->setCode($parameters['code']);
        $voucher->setType($parameters['type']);
        $voucher->setDiscountAmount($parameters['discount_amount']);
        $voucher->setStatus(Voucher::STATUS_NOT_APPLIED);
        $voucher->setExpiresAt(new \DateTime($parameters['expires_at']));
        $voucher->setModifiedDate(new \DateTime());
        return $this->voucherRepository->save($voucher);
    }

    public function getVoucher($condition): array
    {
        return $this->voucherRepository->getVoucherInfo($condition);
    }

    public function getVoucherById($voucher): ?Voucher
    {
        return $this->voucherRepository->getVoucherById($voucher);
    }

    public function deleteVoucher($voucher)
    {
        return $this->voucherRepository->remove($voucher, true);
    }

    public function getActiveVouchers($limit, $offset): array
    {
        return $this->voucherRepository->getActiveVouchers(Voucher::STATUS_NOT_APPLIED, $limit, $offset);
    }

    public function getExpiredVouchers($limit, $offset): array
    {
        return $this->voucherRepository->getExpiredVouchers($limit, $offset);
    }

    public function updateOrderStatus($id, $status)
    {
        return $this->voucherRepository->updateOrderStatus($id, $status);
    }
}
