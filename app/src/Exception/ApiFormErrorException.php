<?php

namespace App\Exception;

class ApiFormErrorException extends \Exception
{
    protected array $errors = [];

    /**
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        parent::__construct("errors",400);
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