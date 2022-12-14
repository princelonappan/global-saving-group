<?php

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\Order;
use App\Entity\Voucher;
use App\Repository\InvitationRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\VoucherRepository;
use App\Service\CommonService;

class OrderService
{
    private $orderRepository;
    private $commonService;

    public function __construct(OrderRepository $orderRepository,  CommonService $commonService)
    {
        $this->orderRepository = $orderRepository;
        $this->commonService = $commonService;
    }

    public function saveOrder($parameters, $customer): Order
    {
        $order = new Order();
        $order->setAmount($parameters['amount']);
        $order->setCreatedDate(new \DateTime());
        $order->setModifiedDate(new \DateTime());
        $order->setStatus(Order::STATUS_CREATED);
        $order->setCustomerId($customer);

        return $this->orderRepository->save($order);
    }

    public function getOrders($limit, $offset): array
    {
        return $this->orderRepository->getOrders($limit, $offset);
    }
}
