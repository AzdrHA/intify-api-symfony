<?php

namespace App\Exception;

class ApiException extends \Exception
{
    private array $jsonError = [];

    public function __construct($message = "Une erreur est survenue", $code = 0, array $jsonError = [])
    {
        parent::__construct($message, $code);
        $this->jsonError = $jsonError;
    }

    /**
     * @return array
     */
    public function getJsonError(): array
    {
        return $this->jsonError;
    }
}