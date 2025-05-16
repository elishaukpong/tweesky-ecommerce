<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ProductAlreadyExistsInWishlist extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'You already have this product in your wishlist',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
