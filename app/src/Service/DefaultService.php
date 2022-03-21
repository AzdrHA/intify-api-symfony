<?php

namespace App\Service;

use App\Exception\ApiFormErrorException;
use App\Utils\UtilsForm;
use App\Utils\UtilsNormalizer;
use ArrayObject;
use Closure;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class DefaultService
{
    protected ?FormFactoryInterface $formFactory = null;

    protected array $listBlackList = [];
    protected array $listWhiteList = [];
    protected array $singleWhiteList = [];
    protected array $singleBlackList = [];
    protected array $commonBlackList = [];

    /**
     * @param Request $request
     * @param string $formClass
     * @param object $entity
     * @param Closure $closure
     * @param array $params
     * @return void
     * @throws ApiFormErrorException
     */
    public function handleForm(Request $request, string $formClass, object $entity, Closure $closure, array $params = []): void
    {
        $form = $this->formFactory->create($formClass, $entity, array_merge(
            ['method' => $request->getMethod()], $params
        ))->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        if($form->isSubmitted() && $form->isValid())
        {
            $closure();
        }

        $errors = UtilsForm::getErrors($form);
        if ($errors)
            throw new ApiFormErrorException($errors);
    }

    /**
     * @param object $object
     * @param array $blackListExtra
     * @param array $whiteListExtra
     * @return array|ArrayObject|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    public function normalizeSingle(object $object, array $blackListExtra = [], array $whiteListExtra = []): float|array|bool|ArrayObject|int|string|null
    {
        $mergedWhite = array_merge($this->singleWhiteList ?? $this->listWhiteList,$whiteListExtra);
        return UtilsNormalizer::normalize($object, [],
            array_merge(($this->singleBlackList ?? $this->listBlackList),$blackListExtra, $this->commonBlackList),
            count($mergedWhite) ? $mergedWhite : null
        );
    }
}