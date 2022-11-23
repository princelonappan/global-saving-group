<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection;


class VoucherRequest
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateVoucherRequest(array $postData): array
    {
        $formattedViolationList = [];
        $constraints = new Collection([
            'description' => [
                new NotBlank()
            ],
            'code' => [
                new NotBlank()
            ],
            'type' => [
                new NotBlank()
            ],
            'discount_amount' => [
                new NotBlank()
            ],
            'expires_at' => [
                new NotBlank()
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