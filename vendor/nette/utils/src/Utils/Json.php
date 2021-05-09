<?php

namespace ECSPrefix20210509\Nette\Utils;

use ECSPrefix20210509\Nette;
/**
 * JSON encoder and decoder.
 */
final class Json
{
    use Nette\StaticClass;
    const FORCE_ARRAY = 0b1;
    const PRETTY = 0b10;
    const ESCAPE_UNICODE = 0b100;
    /**
     * Converts value to JSON format. The flag can be Json::PRETTY, which formats JSON for easier reading and clarity,
     * and Json::ESCAPE_UNICODE for ASCII output.
     * @param  mixed  $value
     * @throws JsonException
     * @param int $flags
     * @return string
     */
    public static function encode($value, $flags = 0)
    {
        $flags = (int) $flags;
        $flags = ($flags & self::ESCAPE_UNICODE ? 0 : \JSON_UNESCAPED_UNICODE) | \JSON_UNESCAPED_SLASHES | ($flags & self::PRETTY ? \JSON_PRETTY_PRINT : 0) | (\defined('JSON_PRESERVE_ZERO_FRACTION') ? \JSON_PRESERVE_ZERO_FRACTION : 0);
        // since PHP 5.6.6 & PECL JSON-C 1.3.7
        $json = \json_encode($value, $flags);
        if ($error = \json_last_error()) {
            throw new \ECSPrefix20210509\Nette\Utils\JsonException(\json_last_error_msg(), $error);
        }
        return $json;
    }
    /**
     * Parses JSON to PHP value. The flag can be Json::FORCE_ARRAY, which forces an array instead of an object as the return value.
     * @return mixed
     * @throws JsonException
     * @param string $json
     * @param int $flags
     */
    public static function decode($json, $flags = 0)
    {
        $json = (string) $json;
        $flags = (int) $flags;
        $forceArray = (bool) ($flags & self::FORCE_ARRAY);
        $value = \json_decode($json, $forceArray, 512, \JSON_BIGINT_AS_STRING);
        if ($error = \json_last_error()) {
            throw new \ECSPrefix20210509\Nette\Utils\JsonException(\json_last_error_msg(), $error);
        }
        return $value;
    }
}
