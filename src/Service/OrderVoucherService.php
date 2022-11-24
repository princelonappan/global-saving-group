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
}
