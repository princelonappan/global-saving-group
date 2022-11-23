<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;


class OrderRequest
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateOrderRequest(array $postData): array
    {
        $formattedViolationList = [];
        $constraints = new Collection([
            'amount' => [
                new NotBlank()
            ],
            'customer_id' => [
                new NotBlank()
            ],
            'voucher' => [
                new Optional()
            ]
        ]);

        $validationResult =  $this->validator->validate($postData, $constraints);

        if ($validationResult->count() > 0) {
            for ($i = 0; $i < $validationResult->count(); $i++) {
                $violation = $validationResult->get($i);
                $formattedViolationList[] = array($violation->getPropertyPath() => $violation->getMessage());
            }
        }

        return $formattedViolationList;
    }
}