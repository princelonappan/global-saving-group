<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection;


class InstitutionRequest
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateInstitutionData(array $postData)
    {
        $constraints = new Collection([
            'search' => [
                new NotBlank()
            ]
        ]);

        return $this->validator->validate($postData, $constraints);
    }
}