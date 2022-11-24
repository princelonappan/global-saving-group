<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Voucher;
use App\Repository\UserRepository;
use App\Repository\VoucherRepository;
use App\Service\CommonService;
use App\Service\CustomerService;
use App\Service\OrderService;
use App\Service\OrderVoucherService;
use App\Service\VoucherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Request\OrderRequest;

class OrderController extends AbstractController
{
    /**
     * @var CommonService
     */
    private $commonService;
    /**
     * @var VoucherService
     */
    private $voucherService;
    /**
     * @var CustomerService
     */
    private $customerService;
    /**
     * @var OrderService
     */
    private $orderService;
    /**
     * @var OrderVoucherService
     */
    private $orderVoucherService;

    public function __construct(CommonService $commonService,
                                VoucherService $voucherService, CustomerService $customerService, OrderService $orderService,
                                OrderVoucherService $orderVoucherService)
    {
        $this->commonService = $commonService;
        $this->voucherService = $voucherService;
        $this->customerService = $customerService;
        $this->orderService = $orderService;
        $this->orderVoucherService = $orderVoucherService;
    }

    /**
     * Get all orders.
     *
     * @Route("/api/orders", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all the orders by pagination",
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page Number"
     * )
     * @OA\Tag(name="Order")
     * @Security(name="Bearer")
     */

    public function index(Request $request): Response
    {
        $page = $request->query->get('page');
        $paginationLimit = Order::PAGINATION_LIMIT;
        $offset = $this->commonService->getPageOffset($paginationLimit, $page);
        $ordersInfo = $this->orderService->getOrders($paginationLimit, $offset);
        $orders = $this->commonService->getFormattedOrders($ordersInfo);
        return new Response(json_encode(array('success' => true, 'orders' => $orders)));
    }

    /**
     * Create Order.
     *
     * @Route("/api/order", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Return order information",
     * )
     *  @OA\RequestBody(
     *          description="amount - Order amount </br> customer_id - Customer id </br> voucher - Optional </br>",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="amount",
     *                     type="integer",
     *                     description="This is the sender user id"
     *                 ),
     *                 @OA\Property(
     *                     property="customer_id",
     *                     type="integer",
     *                     description="This is the reciever user id"
     *                 ),
     *                @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 ),
     *                 example={"amount": "100", "customer_id": "1", "voucher": "12345"}
     *             )
     *         )
     *     ),
     * @OA\Tag(name="Order")
     * @Security(name="Bearer")
     */

    public function create(Request $request, OrderRequest $orderRequest): Response
    {
        $errorMessage = array('success' => false, 'error' => 'Please provide the valid data');
        $parameters = json_decode($request->getContent(), true);
        if (!empty($parameters)) {
            $validation = $orderRequest->validateOrderRequest($parameters);
            if (count($validation) > 0) {
                return new Response(json_encode(array_merge($validation, $errorMessage)));
            }
            $customer = $this->customerService->getCustomer($parameters['customer_id']);
            if (empty($customer)) {
                return new Response(json_encode(array('success' => false, 'message' => 'No Customer found.')));
            }

            if (!empty($parameters['voucher'])) {
                $condition = array('code' => $parameters['voucher']);
                $voucher = $this->voucherService->getVoucher($condition);
                if (!empty($voucher[0])) {
                    $voucherInfo = $voucher[0];
                    if ($voucherInfo->getStatus() == Voucher::STATUS_NOT_APPLIED &&
                        ($voucherInfo->getExpiresAt()->getTimestamp() > strtotime($this->commonService->getCurrentTime()))) {
                        $amount = $parameters['amount'];
                        $discountAmount = $this->commonService->calculateDiscountAmount($voucherInfo->getType(),
                            $voucherInfo->getDiscountAmount(), $amount);
                        if ($discountAmount < $amount) {
                            $orderDetails = $this->orderService->saveOrder($parameters, $customer);
                            if ($orderDetails) {
                                $this->orderVoucherService->saveOrder($orderDetails, $customer, $voucherInfo);
                                $this->voucherService->updateOrderStatus($voucherInfo, Voucher::STATUS_APPLIED);
                                $response = array('order_id' => $orderDetails->getId(), 'discount_amount' => $discountAmount
                                , 'order_amount' => $amount);
                                return new Response(json_encode(array('success' => true,
                                    'message' => 'Successfully created and applied the order and voucher.' . json_encode($response))));
                            } else {
                                return new Response(json_encode(array('success' => false,
                                    'message' => 'Some error occurred. Please try after sometime.')));
                            }
                        } else {
                            return new Response(json_encode(array('success' => false,
                                'message' => 'Voucher amount should be less for the order amount.')));
                        }
                    } else {
                        return new Response(json_encode(array('success' => false, 'message' => 'Voucher is already expired or applied.')));
                    }
                } else {
                    return new Response(json_encode(array('success' => false, 'message' => 'Invalid Voucher Details.')));
                }
            } else {
                $orderDetails = $this->orderService->saveOrder($parameters, $customer);
                $response = array('order_id' => $orderDetails->getId());
                return new Response(json_encode(array('success' => true, 'message' => 'Successfully saved the order. ' . json_encode($response))));
            }
        } else {
            return new Response(json_encode($errorMessage));
        }
    }
}