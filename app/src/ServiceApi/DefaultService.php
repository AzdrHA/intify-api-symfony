<?php

namespace App\ServiceApi;

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
        ));
        $form->submit($request->request->all());

        if($form->isSubmitted() && $form->isValid())
        {
            dump($form->get('file')->getData());
            $closure();
        }

        $errors = UtilsForm::getErrors($form);
        if ($errors)
            throw new ApiFormErrorException($errors);
    }
}