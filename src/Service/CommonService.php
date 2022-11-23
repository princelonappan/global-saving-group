<?php

namespace App\Service;

use App\Entity\Voucher;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommonService
{
    private $client;
    private $errorMessage = array('status' => 503, 'message' => 'Please try after sometime.');

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCurrentTime()
    {
        return date("Y-m-d H:i:s.0000", strtotime('now'));
    }

    public function getPageOffset($limit, $page)
    {
        if($page) {
            return $offset = ($page * $limit) - $limit;
        } else {
            return 0;
        }
    }

    public function calculateDiscountAmount($type, $value, $orderAmount)
    {
        if ($type == Voucher::STATUS_FIXED_TYPE) {
            return $value;
        } else {
            return ($value / 100) * $orderAmount;
        }
    }

    public function getFormatedOrders($ordersInfo)
    {
        $orders = array();
        foreach ($ordersInfo as $order) {
            $result = array(
                'order_id' => $order['id'],
                'order_amount' => $order['amount'],
                'status' => $order['status'],
                'customer_name' => $order['name'],
                'created_date' => $order['created_date'],
            );
            if($order['code']) {
                $voucher['voucher'] = array(
                    'name' => $order['code'],
                    'discount_amount' => $this->calculateDiscountAmount($order['type'], $order['discount_amount'], $order['amount'])
                );
                $result = array_merge($result, $voucher);
            }
            $orders[] = $result;
        }
        return $orders;
    }
}
