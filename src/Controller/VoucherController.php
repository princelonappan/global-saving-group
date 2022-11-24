<?php

namespace App\Controller;

use App\Entity\Voucher;
use App\Request\VoucherRequest;
use App\Service\CommonService;
use App\Service\VoucherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class VoucherController extends AbstractController
{
    /**
     * @var CommonService
     */
    private $commonService;
    /**
     * @var VoucherRequest
     */
    private $voucherRequest;
    /**
     * @var VoucherService
     */
    private $voucherService;

    public function __construct(CommonService $commonService, VoucherRequest $voucherRequest, VoucherService $voucherService)
    {
        $this->commonService = $commonService;
        $this->voucherRequest = $voucherRequest;
        $this->voucherService = $voucherService;
    }

    /**
     * Get Vouchers.
     *
     * @Route("/api/vouchers", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all the vouchers",
     * )
     * @OA\Parameter(
     *     name="type",
     *     in="query",
     *     required=true,
     *     description="Type Name (active|expired)"
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     description="Page Number"
     * )
     * @OA\Tag(name="Voucher")
     * @Security(name="Bearer")
     */

    public function index(Request $request): Response
    {
        $type = $request->query->get('type');
        $page = $request->query->get('page');
        $paginationLimit = Voucher::PAGINATION_LIMIT;
        if (!empty($type)) {
            $offset = $this->commonService->getPageOffset($paginationLimit, $page);
            if ($type == 'active') {
                $vouchers = $this->voucherService->getActiveVouchers($paginationLimit, $offset);
            } else {
                $vouchers = $this->voucherService->getExpiredVouchers($paginationLimit, $offset);
            }
            return new Response(json_encode(array('success' => true, 'vouchers' => $vouchers)));
        } else {
            return new Response(json_encode(array('success' => true, 'message' => 'Please provide valid request.')));
        }
    }

    /**
     * Create Voucher.
     *
     * @Route("/api/voucher", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Return all the voucher information",
     * )
     * @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *         @OA\Property(
     *           property="description",
     *           type="string",
     *           description="This is voucher description"
     *         ),
     *       @OA\Property(
     *          property="code",
     *          type="string",
     *          description="voucher code"
     *        ),
     *        @OA\Property(
     *             property="type",
     *             type="integer",
     *             description="1 - Percentage, 2 - Fixed"
     *         ),
     *        @OA\Property(
     *             property="discount_amount",
     *             type="integer",
     *             description="value"
     *         ),
     *        @OA\Property(
     *             property="expires_at",
     *             type="string",
     *             description="Voucher expires"
     *         ),
     *         example={"description":"sample", "code":"1", "type":"1", "discount_amount": "20", "expires_at":"2022-08-10 21:35:05"}
     *         )
     *       )
     *     ),
     * @OA\Tag(name="Voucher")
     * @Security(name="Bearer")
     */

    public function create(Request $request): Response
    {
        $errorMessage = array('success' => false, 'message' => 'Please provide the valid data');
        $parameters = json_decode($request->getContent(), true);
        if (!empty($parameters)) {
            $validation = $this->voucherRequest->validateVoucherRequest($parameters);
            if (count($validation) > 0) {
                return new Response(json_encode(array_merge($validation, $errorMessage)));
            }
            $condition = array('code' => $parameters['code']);
            $voucher = $this->voucherService->getVoucher($condition);
            if (empty($voucher)) {
                return new Response(json_encode(array('success' => true, 'message' => 'Successfully saved the voucher')));
            } else {
                return new Response(json_encode(array('success' => false, 'message' => 'Already created the voucher code.')));
            }
        } else {
            return new Response(json_encode($errorMessage));
        }
    }

    /**
     * Update Voucher.
     *
     * @Route("/api/voucher/{id}", name="voucher_udpate", methods = {"PUT"})
     * @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *         @OA\Property(
     *           property="description",
     *           type="string",
     *           description="This is voucher description"
     *         ),
     *       @OA\Property(
     *          property="code",
     *          type="string",
     *          description="voucher code"
     *        ),
     *        @OA\Property(
     *             property="type",
     *             type="integer",
     *             description="1 - Percentage, 2 - Fixed"
     *         ),
     *        @OA\Property(
     *             property="discount_amount",
     *             type="integer",
     *             description="value"
     *         ),
     *        @OA\Property(
     *             property="expires_at",
     *             type="string",
     *             description="Voucher expires"
     *         ),
     *         example={"description":"sample", "code":"12345", "type":"1", "discount_amount": "20", "expires_at":"2023-08-10 21:35:05"}
     *         )
     *       )
     *     ),
     * @OA\Tag(name="Voucher")
     * @Security(name="Bearer")
     */

    public function update(Request $request, $id): Response
    {
        $errorMessage = array('success' => false, 'error' => 'Please provide the valid data');
        $parameters = json_decode($request->getContent(), true);
        if (!empty($parameters)) {
            $validation = $this->voucherRequest->validateVoucherRequest($parameters);
            if (count($validation) > 0) {
                return new Response(json_encode(array_merge($validation, $errorMessage)));
            }
            $voucher = $this->voucherService->getVoucherById($id);
            if (!empty($voucher)) {
                if ($voucher->getStatus() == Voucher::STATUS_NOT_APPLIED &&
                    ($voucher->getExpiresAt()->getTimestamp() > strtotime($this->commonService->getCurrentTime()))) {
                    $voucherDetails = $this->voucherService->updateVoucher($voucher, $parameters);
                    $response = array('voucher_id' => $voucherDetails->getId());
                    return new Response(json_encode(array('success' => true, 'message' => 'Successfully updated the voucher. ' . json_encode($response))));
                } else {
                    return new Response(json_encode(array('success' => false, 'message' => 'The Voucher is already used/expired.')));
                }
            } else {
                return new Response(json_encode(array('success' => false, 'message' => 'No voucher code found.')));
            }
        } else {
            return new Response(json_encode($errorMessage));
        }
    }

    /**
     * Update Voucher.
     *
     * @Route("/api/voucher/{id}", name="voucher_delete", methods = {"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="",
     * )
     * @OA\Tag(name="Voucher")
     * @Security(name="Bearer")
     */

    public function delete(Request $request, $id): Response
    {
        $errorMessage = array('success' => false, 'error' => 'Please provide the valid data');
        if (!empty($id)) {
            $voucher = $this->voucherService->getVoucherById($id);
            if (!empty($voucher)) {
                if ($voucher->getStatus() == Voucher::STATUS_NOT_APPLIED &&
                    ($voucher->getExpiresAt()->getTimestamp() > strtotime($this->commonService->getCurrentTime()))) {
                    $this->voucherService->deleteVoucher($voucher);
                    return new Response(json_encode(array('success' => true, 'message' => 'Successfully deleted the voucher. ')));
                } else {
                    return new Response(json_encode(array('success' => false, 'message' => 'The Voucher is already used/expired.')));
                }
            } else {
                return new Response(json_encode(array('success' => false, 'message' => 'No voucher code found.')));
            }
        } else {
            return new Response(json_encode($errorMessage));
        }
    }
}