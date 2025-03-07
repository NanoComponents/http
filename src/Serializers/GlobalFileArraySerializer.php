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
    /**
     * @var array<string, array<int|string, array<string, mixed>|int|string>>
     */
    private static array $arr = [];
    /**
     * @var array<string>
     */
    private static array $fieldPath = [];

    /**
     * @param array<string, array<string, mixed>> $globalArray
     * @return array<mixed>
     */
    public static function serialize(array $globalArray): array
    {
        return self::iterateTheField($globalArray);
    }

    /**
     * @param array<string, array<string, mixed>> $array
     * @return array<string, mixed>
     */
    protected static function iterateTheField(array $array): array
    {
        /** @var array<string, array<string, mixed>> $result */
        $result = [];

        foreach ($array as $fieldName => $value) {
            // Check if it's a $_FILES-style flat structure (keys are all numerically indexed arrays)
            if (self::allKeysAreIndexedArrays($value)) {
                /**
                 * @var array<int|string, array<int|string, mixed>> $value
                 */
                $result[$fieldName] = self::restructureFlatFileArray($value);
                continue;
            }

            // handle the nested structure
            foreach ($value as $fieldParamName => $paramArray) {
                if (is_array($paramArray)) {
                    $result[$fieldName] = self::iterate($paramArray, $fieldParamName);
                } else {
                    if (!isset($result[$fieldName])) {
                        $result[$fieldName] = [];
                    }
                    $result[$fieldName] =  $result[$fieldName];

                    $result[$fieldName][$fieldParamName] = $paramArray;
                }
            }
            self::resetValues();
        }

        return $result;
    }

    protected static function resetValues(): void
    {
        self::$arr       = [];
        self::$fieldPath = [];
    }

    /**
     * @param array<mixed> $array
     * @return bool
     */
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
     * @param array<int|string, array<int|string, mixed>> $array
     * @return array<mixed>
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

    /**
     * Recursively iterates over an array to build the structured result.
     *
     *
     * @param array<mixed>|null $array The array to iterate over
     * @param string|null $fieldParamName The current field parameter name
     * @return array<string, array<int|string, array<string, mixed>|int|string>>
     */
    protected static function iterate(?array $array = null, ?string $fieldParamName = null): array
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
                        if (!is_array(self::$arr[$fieldString][$index])) {
                            self::$arr[$fieldString][$index] = [];
                        }
                        if (isset($currentParam)) {
                            self::$arr[$fieldString][$index][$currentParam] = $fileValue;
                        }
                    }
                } elseif (is_string($value) || is_int($value)) {
                    $fieldString = implode('.', self::$fieldPath);
                    if (!self::$fieldPath) {
                        if (isset($currentParam, self::$arr[$fieldString])) {
                            self::$arr[$fieldString][$currentParam] = $value;
                        }
                    } else if (isset($currentParam) && is_array(self::$arr[$fieldString][0]) && is_array(self::$arr[$fieldString][0][$currentParam])) {
                        self::$arr[$fieldString][0][$currentParam] = $value;
                    }
                } elseif (is_array($value)) {
                    if (isset($currentParam)) {
                        self::iterate($value, $currentParam);
                    }
                }

                if (is_string($key)) {
                        array_pop(self::$fieldPath);
                }
            }
        }

        $result = self::$arr;

        return $result;
    }

}
