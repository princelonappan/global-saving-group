<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\InvitationRepository;

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
