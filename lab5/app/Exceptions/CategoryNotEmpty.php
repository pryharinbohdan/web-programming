<?php

namespace App\Exceptions;

use Exception;

class CategoryNotEmpty extends Exception
{
    protected $message = 'Категорія ще містить продукти.';
}
