<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VersionController
 * @package App\Controller
 * @Rest\Route("/version")
 */
class VersionController extends DefaultApiController
{
    /**
     * @Rest\Get("")
     * @param Request $request
     * @param string $app_version
     * @return JsonResponse
     */
    public function versionAction(Request $request, string $app_version = "1.0"): JsonResponse
    {
        return new JsonResponse($app_version);
    }
}