<?php

namespace  Meshachviktor\SecureRandom;

use RangeException;
use Meshachviktor\SecureRandom\Exception\ValueException;

class SecureRandom {

    private const BYTE_LENGTH = 64;
    private const MINIMUM_STRING_LENGTH = 1;
    private const MAXIMUM_STRING_LENGTH = 64;
    private const MINIMUM_INTEGER_LENGTH = 1;
    private const MAXIMUM_INTEGER_LENGTH = 19;
    private const MINIMUM_FLOAT_FRACTIONAL_DIGITS = 1;
    private const MAXIMUM_FLOAT_FRACTIONAL_DIGITS = 14;
    private const STRING_LENGTH_ERROR_MESSAGE = 'The value of $length must be between 1 and 64.';
    private const INTEGER_LENGTH_ERROR_MESSAGE = 'The value of $length must be between 1 and 19.';
    private const FLOAT_FRACTION_ERROR_MESSAGE = 'The value of $fractional_digits must be between 1 and 14.';
    private const FLOAT_RANGE_ERROR_MESSAGE = 'The value of $min and $max must be between 0.1 and 0.99999999999999.';
    private const MIN_MAX_ERROR_MESSAGE = 'The value of $min must be less than or equal to the value of $max.';

    private static function throwRangeException(string $message) :void {
        throw new RangeException($message);
    }

    private static function throwValueException(string $message) :void {
        throw new ValueException($message);
    }

    /**
     * Generates random bytes of given length.
     * 
     * @param int $length The number of bytes to generate.
     * @throws \RangeException If the value of $length is less than 1 or greater than 64.
     * @return string
     */
    public static function bytes(int $length = 64) :string {
        if ($length < self::MINIMUM_STRING_LENGTH || $length > self::MAXIMUM_STRING_LENGTH) {
            self::throwRangeException(self::STRING_LENGTH_ERROR_MESSAGE);
        }
        return random_bytes($length);
    }

    /**
     * Generates negative random floating point numbers of given fractional_digits.
     * 
     * @param int $fractional_digits The number of fractional digits the generated 
     *                               floating point number should have.
     * @throws \RangeException If the value of $fractional_digits is less than 1 or greater than 14.
     * @return float
     */
    public static function float(int $fractional_digits = 14) :float {
        if ($fractional_digits < self::MINIMUM_FLOAT_FRACTIONAL_DIGITS || $fractional_digits > self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS) {
            self::throwRangeException(self::FLOAT_FRACTION_ERROR_MESSAGE);
        }
        $operands = [-1, 1];
        return round((float) ('0.' . (string) self::positiveInteger()) * $operands[random_int(0, 1)], $fractional_digits);
    }

    /**
     * Generates positive random floating point numbers of given fractional_digits.
     * 
     * @param int $fractional_digits The number of fractional digits the generated 
     *                               floating point number should have.
     * @throws \RangeException If the value of $fractional_digits is less than 1 or greater than 14.
     * @return float
     */
    public static function positiveFloat(int $fractional_digits = 14) :float {
        if ($fractional_digits < self::MINIMUM_FLOAT_FRACTIONAL_DIGITS || $fractional_digits > self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS) {
            self::throwRangeException(self::FLOAT_FRACTION_ERROR_MESSAGE);
        }
        return round((float) ('0.' . (string) self::positiveInteger()), $fractional_digits);
    }

    /**
     * Generates negative random floating point numbers of given fractional_digits.
     * 
     * @param int $fractional_digits The number of fractional digits the generated 
     *                               floating point number should have.
     * @throws \RangeException If the value of $fractional_digits is less than 1 or greater than 14.
     * @return float
     */
    public static function negativeFloat(int $fractional_digits = 14) :float {
        if ($fractional_digits < self::MINIMUM_FLOAT_FRACTIONAL_DIGITS || $fractional_digits > self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS) {
            self::throwRangeException(self::FLOAT_FRACTION_ERROR_MESSAGE);
        }
        return round((float) ('0.' . (string) self::positiveInteger()) * -1, $fractional_digits);
    }

    /**
     * Generates positive random floating point numbers between the range of min and max.
     * 
     * @param int $min The minimum floating point number to generate.
     * @param int $max The maximum floating point number to generate.
     * @throws \RangeException If the value of $min or $max is outside the range 0.1 - 0.99999999999999.
     * @throws Meshachviktor\SecureRandom\Exception\ValueException If $min is greater than $max.
     * @return float
     */
    public static function floatBetween(float $min, float $max) :float {
        $str_float_min = (string) $min;
        $str_float_max = (string) $max;
        // segments: arr[0] = '0.', arr[1] = decimal digits
        $min_segments = null;
        $max_segments = null;
        $str_int_min = null;
        $str_int_max = null;
        $int_min = null;
        $int_max = null;
        $int_float = null;
        $int_float_length = null;
        $float = null;

        if (stripos($str_float_min, '0.') !== 0 || stripos($str_float_max, '0.') !== 0) {
            self::throwRangeException(self::FLOAT_RANGE_ERROR_MESSAGE);
        }        
        if (strlen($str_float_min) > 16 || strlen($str_float_max) > 16) {
            self::throwRangeException(self::FLOAT_RANGE_ERROR_MESSAGE);
        }
        // get decimal parts
        $min_segments = explode('.', $str_float_min);
        $max_segments = explode('.', $str_float_max);
        $str_int_min = $min_segments[1];
        $str_int_max = $max_segments[1];
        // make sure the decimal digit part does not starts with 0
        if (stripos($str_int_min, '0') === 0 || stripos($str_int_max, '0') === 0) {
            self::throwRangeException(self::FLOAT_RANGE_ERROR_MESSAGE);
        }
        // pad $str_int_min and $str_int_max with zeros if necessary
        if (strlen($min_segments[1]) < self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS) {
            $decimal_diff = self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS - strlen($min_segments[1]);
            for ($iter = 0; $iter <= $decimal_diff - 1; $iter++) {
                $str_int_min .= '0';
            }
        }
        if (strlen($max_segments[1]) < self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS) {
            $decimal_diff = self::MAXIMUM_FLOAT_FRACTIONAL_DIGITS - strlen($max_segments[1]);
            for ($iter = 0; $iter <= $decimal_diff - 1; $iter++) {
                $str_int_max .= '0';
            }
        }
        $int_min = (int) $str_int_min;
        $int_max = (int) $str_int_max;
        if ($int_min > $int_max) {
            self::throwValueException(self::MIN_MAX_ERROR_MESSAGE);
        }
        $int_float = random_int($int_min, $int_max);
        $int_float_length = strlen((string) $int_float);
        return $int_float * (pow(10, ($int_float_length * -1)));
    }

    /**
     * Generates random integers between the range PHP_INT_MIN and PHP_INT_MAX.
     * 
     * @return int
     */    
    public static function integer() :int {
        return random_int(PHP_INT_MIN, PHP_INT_MAX);
    }

    /**
     * Generates random positive integers of given length.
     * 
     * @param int $length The length of the integer to generate.
     * @throws \RangeException If the value of $length is less than 1 or greater than 19.
     * @return int
     */
    public static function positiveInteger(int $length = 19) :int {
        if ($length < self::MINIMUM_INTEGER_LENGTH || $length > self::MAXIMUM_INTEGER_LENGTH) {
            self::throwRangeException(self::INTEGER_LENGTH_ERROR_MESSAGE);
        }
        if ($length === 19) {
            return random_int(pow(10, ($length - 1)), PHP_INT_MAX);
        }
        return random_int(pow(10, ($length - 1)), pow(10, $length) - 1);
    }

    /**
     * Generates random negative integers of given length.
     * 
     * @param int $length The length of the integer to generate.
     * @throws \RangeException If the value of $length is less than 1 or greater than 19.
     * @return int
     */
    public static function negativeInteger(int $length = 19) :int {
        if ($length < self::MINIMUM_INTEGER_LENGTH || $length > self::MAXIMUM_INTEGER_LENGTH) {
            self::throwRangeException(self::INTEGER_LENGTH_ERROR_MESSAGE);
        }
        if ($length === 19) {
            return random_int(PHP_INT_MIN, pow(10, ($length - 1)) * -1);
        }
        return random_int((pow(10, $length) - 1) * -1, pow(10, ($length - 1)) * -1);
    }

    public static function integerBetween(int $min, int $max) :int {
        if ($min > $max) {
            self::throwValueException(self::MIN_MAX_ERROR_MESSAGE);
        }
        return random_int($min, $max);
    }
    
    /**
     * Generates random hexadecimal strings of given length.
     * 
     * @param int $length The length of the string to generate.
     * @throws \RangeException If the value of $length is less than 1 or greater than 64.
     * @return string
     */
    public static function hexadecimalString(int $length = 64) :string {
        if ($length < self::MINIMUM_STRING_LENGTH || $length > self::MAXIMUM_STRING_LENGTH) {
            self::throwRangeException(self::STRING_LENGTH_ERROR_MESSAGE);
        }
        return substr(bin2hex(utf8_encode(self::bytes(self::BYTE_LENGTH))), 0, $length);
    }

    /**
     * Generates mixed case alphanumeric strings of given length.
     * 
     * @param int $length The length of the string to generate.
     * @throws \RangeException If the value of $length is less than 1 or greater than 64.
     * @return string
     */
    public static function alphanumericString(int $length = 64) :string {
        if ($length < self::MINIMUM_STRING_LENGTH || $length > self::MAXIMUM_STRING_LENGTH) {
            self::throwRangeException(self::STRING_LENGTH_ERROR_MESSAGE);
        }
        return substr(preg_replace('#[\+\/=]#', '', base64_encode(self::bytes(self::BYTE_LENGTH))), 0, $length);
    }

    /**
     * Generates version 4 UUIDs (RFC 4122).
     * 
     * @return string
     */
    public static function uuid() :string {
        $bytes = self::bytes(16);
        $time_low = bin2hex(substr($bytes, 0, 4));
        $time_mid = bin2hex(substr($bytes, 4, 2));
        $time_high_and_version = bin2hex(substr($bytes, 6, 2));
        $clock_seq_and_reserved = bin2hex(substr($bytes, 8, 1));
        $clock_seq_low = bin2hex(substr($bytes, 9, 1));
        $node = bin2hex(substr($bytes, 10, 6));
        $uuid = null;
        /* 
        * Set the two most significant bits (bits 6 and 7) of the
        * clock-seq-and-reserved to zero and one, respectively
        */ 
        // set bit 7 of clock_seq_and_reserved to 1
        $clock_seq_and_reserved = base_convert(base_convert($clock_seq_and_reserved, 16, 10) | (1 << 7), 10, 16);
        // set bit 6 of clock_seq_and_reserved to 0
        $clock_seq_and_reserved = base_convert(base_convert($clock_seq_and_reserved, 16, 10) & ~(1 << 6), 10, 16);
        /* 
        * Set the four most significant bits (bits 12 through 15) of 
        * the time-high-and-version field to 0100
        */
        // set bit 15 of time_high_and_version to 0
        $time_high_and_version = base_convert(base_convert($time_high_and_version, 16, 10) & ~(1 << 15), 10, 16);
        // set bit 14 of time_high_and_version to 1
        $time_high_and_version = base_convert(base_convert($time_high_and_version, 16, 10) | (1 << 14), 10, 16);
        // set bit 13 of time_high_and_version to 0
        $time_high_and_version = base_convert(base_convert($time_high_and_version, 16, 10) & ~(1 << 13), 10, 16);
        // set bit 12 of time_high_and_version to 0
        $time_high_and_version = base_convert(base_convert($time_high_and_version, 16, 10) & ~(1 << 12), 10, 16);
        $uuid = "${time_low}-${time_mid}-${time_high_and_version}-${clock_seq_and_reserved}${clock_seq_low}-${node}";
        return $uuid;
    }

}