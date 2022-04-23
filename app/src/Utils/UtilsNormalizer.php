<?php

namespace App\Utils;

use ArrayObject;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

function callback_datetime($innerObject): ?string
{
    return ($innerObject instanceof \DateTime || $innerObject instanceof \DateTimeImmutable) ? $innerObject->format("Y-m-d H:i:s") : null;
}

function callback_date($innerObject): ?string
{
    return ($innerObject instanceof \DateTime || $innerObject instanceof \DateTimeImmutable) ? $innerObject->format("Y-m-d") : null;
}

abstract class UtilsNormalizer
{
    const DEFAULT_CALLBACKS = [
        "createdAt" => "\app\utils\callback_datetime",
        "updatedAt" => "\app\utils\callback_datetime",
        "joinAt" => "\app\utils\callback_datetime",
        "lastLoginAt" => "\app\utils\callback_datetime",
    ];

    /**
     * @param object|null $object
     * @param array $callbacks
     * @param array $ignored_attrs
     * @param array|null $whitelist_attrs
     * @return array
     * @throws ExceptionInterface
     */
    static function normalize(?object $object, array $callbacks = [], array $ignored_attrs = [], ?array $whitelist_attrs = null): array
    {
        if (!$object)
            return [];
        if (is_array($callbacks) && count($callbacks))
            $callbacks = array_merge(self::DEFAULT_CALLBACKS, $callbacks);
        else
            $callbacks = self::DEFAULT_CALLBACKS;
        $encoders = [new JsonEncoder()];
        $array_callback = [];

        foreach ($callbacks as $k => $fn) {
            $fn_explode = explode('\\', $fn);
            $fn = __NAMESPACE__ . "\\" . array_pop($fn_explode);
            if (function_exists($fn)) {
                $array_callback[$k] = function ($innerObject) use ($fn) {
                    return call_user_func($fn, $innerObject);
                };
            }
        }

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => $array_callback,
        ];
        $normalizers = [new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext)];
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

    /**
     * @param array $tab
     * @param array|null $callbacks
     * @param array $blackListKeys
     * @param array $whitelistKeys
     * @return void
     */
    static function normalizeArrayList(array &$tab, ?array $callbacks = null, array $blackListKeys = [], array $whitelistKeys = []): void
    {
        foreach ($tab as &$objectArray) {
            self::normalizeArray($objectArray, $callbacks, $blackListKeys, $whitelistKeys);
        }
    }

    /**
     * @param $objectArray
     * @param array|null $callbacks
     * @param array $blackListKeys
     * @param array $whitelistKeys
     * @return void
     */
    static function normalizeArray(&$objectArray, ?array $callbacks = null, array $blackListKeys = [], array $whitelistKeys = []): void
    {
        $callbacks = is_array($callbacks) ? array_merge(self::DEFAULT_CALLBACKS, $callbacks) : self::DEFAULT_CALLBACKS;
        foreach ($objectArray as $key => &$val) {
            if (is_array($blackListKeys) && in_array($key, $blackListKeys, true))
                unset($objectArray[$key]);
            elseif (is_array($val))
                self::normalizeArray($val, $callbacks, $blackListKeys, $whitelistKeys);
            elseif (preg_match("#[a-zA-Z]+#", $key) && in_array($key, array_keys($callbacks))) {
                $objectArray[$key] = $callbacks[$key]($val);
            }
        }
    }
}