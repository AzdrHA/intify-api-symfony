<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class DefaultApiController extends AbstractController
{
    protected LoggerInterface $logger;
    protected LoggerInterface $timeResponseLogger;
    public function __construct(LoggerInterface $logger, LoggerInterface $timeResponseLogger)
    {
        $this->logger = $logger;
        $this->timeResponseLogger = $timeResponseLogger;
    }

    /**
     * @param Request $request
     * @param array $context
     * @return JsonResponse
     */
    public function handleRequest(Request $request, array $context): JsonResponse
    {
        $this->logger->notice(sprintf("%s %s",$request->attributes->get('_route'),$request->getMethod()));
        $beginTime = microtime(true);

        try {
            $service = $context["service"] ?? null;
            $fn = $context["function"] ?? null;
            $fn_args = $context["args"] ?? [];

            $res = $service->$fn($request, ...$fn_args);
        } catch(ApiException $e){
            $this->logger->error($e->getMessage(), ["uri" => $request->getUri()]);
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode()>400 ? $e->getCode(): 400);
        } catch(ApiFormErrorException $e){
            $this->logger->error($e->getMessage(), ["uri" => $request->getUri(), "errors" => $e->getErrors()]);
            return new JsonResponse([
                "error" => $e->getMessage() ?? "Une erreur est survenue",
                "errors_detail" => $e->getErrors()
            ], max($e->getCode(), 400));
        } catch (\Exception $e) {
            if ($this->getParameter('env') === "dev")
                dump($e);

            return new JsonResponse(['error' => 'An error has occurred'], 400);
        } finally {
            $endTime = microtime(true);
            $time = $endTime-$beginTime;
            $time = intval(number_format($time*1000,0, ',', ''));
            $level = "info";
            if($time > 300) $level = "warning";
            if($time > 600) $level = "error";
            $this->timeResponseLogger->log($level,sprintf("%s ms | %s : %s", $time, $request->getPathInfo(), $request->getMethod()));
        }

        return new JsonResponse($res);
    }
}