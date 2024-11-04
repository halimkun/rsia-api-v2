<?php

namespace App\Helpers;

class SafeAccess
{
    public static function object($object, $propertyPath, $default = null)
    {
        $properties = explode('->', $propertyPath);
        foreach ($properties as $property) {
            if (!isset($object) || !isset($object->$property)) {
                return $default;
            }
            $object = $object->$property;
        }
        return $object;
    }
}
