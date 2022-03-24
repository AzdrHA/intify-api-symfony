<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;

class ApiFormErrorException extends \Exception
{
    protected array $errors = [];

    /**
     * @param array $errors
     */
    #[Pure] public function __construct(array $errors = [])
    {
        parent::__construct("Form invalidation",400);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}