<?php

namespace App\Utils;

use ArrayObject;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class UtilsNormalizer
{
    /**
     * @param object|null $object
     * @param array $callbacks
     * @param array $ignored_attrs
     * @param array|null $whitelist_attrs
     * @return array|ArrayObject|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    static function normalize(?object $object, array $callbacks = [], array $ignored_attrs = [], ?array $whitelist_attrs = null): float|array|bool|ArrayObject|int|string|null
    {
        if(!$object)
            return [];
        if(is_array($callbacks) && count($callbacks))
            $callbacks = array_merge([],$callbacks);
        else
            $callbacks = [];
        $encoders = [new JsonEncoder()];
        $array_callback = [];

        foreach($callbacks as $k => $fn){
            $fn_explode = explode('\\',$fn);
            $fn = __NAMESPACE__."\\".array_pop($fn_explode);
            if(function_exists($fn)){
                $array_callback[$k] = function($innerObject) use ($fn){return call_user_func($fn, $innerObject);};
            }
        }

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => $array_callback,
        ];
        $normalizers = [new GetSetMethodNormalizer(null,null,null,null,null,$defaultContext)];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->normalize($object, null, [
            AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 1,
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => $ignored_attrs,
            AbstractNormalizer::ATTRIBUTES => $whitelist_attrs,
        ]);
    }
}