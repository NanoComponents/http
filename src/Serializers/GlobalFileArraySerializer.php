<?php

namespace NanoLibs\Http\Serializers;

use function array_keys;
use function array_pop;
use function count;
use function is_array;
use function is_string;
use function implode;
use function range;

final class GlobalFileArraySerializer
{
    private static $arr = [];
    private static $fieldPath = [];

    public static function serialize(array $globalArray): array
    {
        self::iterateTheField([]);
        return self::iterateTheField($globalArray);
    }

    protected static function iterateTheField(array $array)
    {
        $result = [];

        foreach ($array as $fieldName => $value) {
            // Check if it's a $_FILES-style flat structure (keys are all numerically indexed arrays)
            if (is_array($value) && self::allKeysAreIndexedArrays($value)) {
                $result[$fieldName] = self::restructureFlatFileArray($value);
                continue;
            }

            // handle the nested structure
            foreach ($value as $fieldParamName => $paramArray) {
                if (is_array($paramArray)) {
                    $result[$fieldName] = self::iterate($paramArray, $fieldParamName);
                } else {
                    $result[$fieldName][$fieldParamName] = $paramArray;
                }
            }
            self::resetValues();
        }

        return $result;
    }

    protected static function resetValues()
    {
        self::$arr       = [];
        self::$fieldPath = [];
    }

    protected static function allKeysAreIndexedArrays(array $array): bool
    {
        foreach ($array as $value) {
            if (!is_array($value) || array_keys($value) !== range(0, count($value) - 1)) {
                return false;
            }
        }
        return true;
    }

    /**
     * restructures a flat $_FILES array into a structured format
     */
    protected static function restructureFlatFileArray(array $array): array
    {
        $result = [];
        foreach ($array as $paramName => $values) {
            foreach ($values as $index => $value) {
                $result[$index][$paramName] = $value;
            }
        }
        return $result;
    }

    
    protected static function iterate(?array $array = null, ?string $fieldParamName = null)
    {
        if ($fieldParamName) {
            $currentParam = $fieldParamName;
        }
    
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    self::$fieldPath[] = $key;
                }
    
                if (is_array($value) && array_keys($value) === range(0, count($value) - 1)) {
                    $fieldString = implode('.', self::$fieldPath);
                    foreach ($value as $index => $fileValue) {
                        self::$arr[$fieldString][$index][$currentParam] = $fileValue;
                    }
                } elseif (is_string($value) || is_int($value)) {
                    $fieldString = implode('.', self::$fieldPath);
                    if (!self::$fieldPath) {
                        self::$arr[$fieldString][$currentParam] = $value;
                    } else {
                        self::$arr[$fieldString][0][$currentParam] = $value;
                    }
                } elseif (is_array($value)) {
                    self::iterate($value, $currentParam);
                }
    
                if (is_string($key)) {
                    if (is_array(self::$fieldPath)) {
                        array_pop(self::$fieldPath);
                    }
                }
            }
        }
    
        $result = self::$arr;
    
        return $result;
    }

}
