<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Invitation;
use App\Entity\Order;
use App\Entity\Voucher;
use App\Repository\CustomerRepository;
use App\Repository\InvitationRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\VoucherRepository;
use App\Service\CommonService;

class CustomerService
{
    private $customerRepository;
    private $commonService;

    public function __construct(CustomerRepository $customerRepository,  CommonService $commonService)
    {
        $this->customerRepository = $customerRepository;
        $this->commonService = $commonService;
    }

    public function getCustomer($id): Customer
    {
        return $this->customerRepository->getCustomer($id);
    }
}
