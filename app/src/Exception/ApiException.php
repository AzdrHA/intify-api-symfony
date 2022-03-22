<?php

namespace App\Exception;

use Throwable;

class ApiException extends \Exception
{
    private string $jsonError = "";

    public function __construct($message = "Une erreur est survenue", $code = 0, Throwable $previous = null, string $jsonError = "")
    {
        parent::__construct($message, $code, $previous);
        $this->jsonError = $jsonError;
    }

    /**
     * @return string
     */
    public function getJsonError(): string
    {
        return $this->jsonError;
    }

    /**
     * @param string $jsonError
     */
    public function setJsonError(string $jsonError): void
    {
        $this->jsonError = $jsonError;
    }
}