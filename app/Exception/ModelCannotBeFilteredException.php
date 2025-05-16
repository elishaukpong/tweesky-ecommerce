<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ModelCannotBeFilteredException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'You need to implement Filterable Contract on the model you are filtering!',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
