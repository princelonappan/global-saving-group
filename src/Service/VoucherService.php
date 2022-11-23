<?php

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\Voucher;
use App\Repository\InvitationRepository;
use App\Repository\UserRepository;
use App\Repository\VoucherRepository;
use App\Service\CommonService;

class VoucherService
{
    private $voucherRepository;
    private $commonService;

    public function __construct(VoucherRepository $voucherRepository,  CommonService $commonService)
    {
        $this->voucherRepository = $voucherRepository;
        $this->commonService = $commonService;
    }

    public function saveVoucher($parameters): Voucher
    {
        $voucher = new Voucher();
        $voucher->setDescription($parameters['description']);
        $voucher->setCode($parameters['code']);
        $voucher->setType($parameters['type']);
        $voucher->setDiscountAmount($parameters['discount_amount']);
        $voucher->setStatus(Voucher::STATUS_NOT_APPLIED);
        $voucher->setExpiresAt(new \DateTime($parameters['expires_at']));
        $voucher->setCreatedDate(new \DateTime());
        $voucher->setModifiedDate(new \DateTime());
        return $this->voucherRepository->save($voucher);
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

    public function updateOrderStatus($voucher, $status)
    {
        return $this->voucherRepository->updateOrderStatus($voucher, $status);
    }
}
