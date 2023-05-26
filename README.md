
# **Introduction**

SecureRandom is a library designed for generating random values. With SecureRandom you can generate the following types of values:

- Raw bytes
- Floating point numbers
- Integers
- Hexadecimal strings
- Alphanumeric strings (mixed case)
- Universally Unique Identifiers (Version 4 UUID)

All values generated by SecureRandom come from cryptographically secure sources. Integers and Floating point numbers generated by SecureRandom are sourced from PHP's `random_int()` function. All other types are sourced from the `random_bytes()` function. These functions, according to the PHP documentation, are suitable for cryptographic use as the values they produce are from a Cryptographically Secure Pseudo-Random Number Generator (CSPRNG). This randomness of the values generated by these functions consequentially applies to the values generated by SecureRandom.

SecureRandom is useful for generating the following:

- One-Time Passwords
- Temporary/default passwords
- Two-Factor Authentication (2FA) codes
- API keys
- Password salts
- File names

The list above is not exhaustive. You can use SecureRandom for a lot more.

# **Usage**

To use SecureRandom add the dependency to your project using the following composer command.

```
$ composer require meshachviktor/secure-random
```

Then import the library to your project as shown below.

```php
use Meshachviktor\SecureRandom\SecureRandom;
```


## **Generating random bytes**

You can generate random bytes using the library by calling the `bytes()` method. 

## Meshachviktor\SecureRandom\SecureRandom::bytes(int $length) :string

This method takes an optional integer argument named $length. By default $length has a value of 64 but can be any number between the range of 1 and 64 (inclusive). Supplying a value less than 1 or greater than 64 will result in the method throwing a `\RangeException`. The `bytes()` method is limited to returning a maximum of 64 bytes for a lot of reasons. If you need more than 64 bytes use PHP's built in `random_bytes()` instead.

## Examples

```php
$bytes = SecureRandom::bytes();    // Returns 64 bytes.
$bytes = SecureRandom::bytes(1);   // Returns 1 byte.
$bytes = SecureRandom::bytes(32);  // Returns 32 bytes.
$bytes = SecureRandom::bytes(0);   // Throws \RangeException
$bytes = SecureRandom::bytes(65);  // Throws \RangeException
```

## **Generating random floats**

The library provides some methods for generating random floating point numbers. The fractional part of all floating point numbers generated are limited to a maximum of 14 decimal digits. All numbers returned by these methods are less than 1. Negative values are in the range of -0.99999999999999 to -0.1 while positive values are in the range of 0.1 to 0.99999999999999.

## Meshachviktor\SecureRandom\SecureRandom::float(int $fractional_digits = 14) :float

The `float()` method returns a random positive or negative floating point number. The method takes an optional integer argument, $fractional_digits, whose value defaults to 14. Supplying a value less than 1 or greater than 14 causes the method to throw `\RangeException`.

## Examples
```php
$float = SecureRandom::float();    // 0.13456788654122
$float = SecureRandom::float(2);   // 0.41
$float = SecureRandom::float(10);  // -0.5774096379
$float = SecureRandom::float(0);   // Throws \RangeException
$float = SecureRandom::float(15);  // Throws \RangeException
```
## Meshachviktor\SecureRandom\SecureRandom::positiveFloat(int $fractional_digits = 14) :float

The `positiveFloat()` method returns a random positive floating point number. The method takes an optional integer argument, $fractional_digits, whose value defaults to 14. Supplying a value less than 1 or greater than 14 causes the method to throw `\RangeException`.

## Examples

```php
$float = SecureRandom::positiveFloat();    // 0.92120198810972
$float = SecureRandom::positiveFloat(2);   // 0.12
$float = SecureRandom::positiveFloat(10);  // 0.7222526563
$float = SecureRandom::positiveFloat(0);   // Throws \RangeException 
$float = SecureRandom::positiveFloat(15);  // Throws \RangeException  
```

## Meshachviktor\SecureRandom\SecureRandom::negativeFloat(int $fractional_digits = 14) :float

The `negativeFloat()` method returns a random negative floating point number. The method takes an optional integer argument, `$fractional_digits`, whose value defaults to 14. Supplying a value less than 1 or greater than 14 causes the method to throw `\RangeException`.

## Examples

```php
$float = SecureRandom::negativeFloat();    // -0.13456788654122
$float = SecureRandom::negativeFloat(2);   // -0.41
$float = SecureRandom::negativeFloat(10);  // -0.5774096379
$float = SecureRandom::negativeFloat(0);   // Throws \RangeException
$float = SecureRandom::negativeFloat(15);  // Throws \RangeException
```

## Meshachviktor\SecureRandom\SecureRandom::floatBetween(float $min, float $max) :float

The `floatBetween()` method takes two float arguments `$min` and `$max` and returns a floating point number between the range of $min and $max (inclusive). The method accepts only positive float values and as a results only returns positive values. The method will throw `\RangeException` if the value of $min or $max is outside of the range 0.1 and 0.99999999999999. The method will also throw `Meshachviktor\SecureRandom\Exception\ValueException` if the value of `$min` is greater than the value of `$max`.

## Examples

```php
$float = SecureRandom::floatBetween(0.1, 0.2);                        // 0.16708918081238
$float = SecureRandom::floatBetween(0.7777, 0.8888);                  // 0.8713798653067
$float = SecureRandom::floatBetween(0.25555555, 0.9999999999999999);  // Throws \RangeException
$float = SecureRandom::floatBetween(0.3, 0.2);                        // Throws ValueException
```

## Important note

Due to the effect of rounding, the fractional parts of values returned by functions that generate floats will sometimes be one less than the value of `$fractional_digits`.


## **Generating random integers**

The library provides some functions for generating random integers. Values returned by these functions are between `PHP_INT_MIN` and `PHP_INT_MAX` (inclusive).

## Meshachviktor\SecureRandom\SecureRandom::integer() :int

The `integer()` method takes no argument and returns a value between `PHP_INT_MIN` and `PHP_INT_MAX`.

## Examples

```php
$integer = SecureRandom::integer();    // 3733559955175437818
$integer = SecureRandom::integer();    // -8615169409373723479
$integer = SecureRandom::integer();    // 898224759918621175
$integer = SecureRandom::integer();    // -4550407951101420676
```

## Meshachviktor\SecureRandom\SecureRandom::positiveInteger(int $length = 19) :int

The `positiveInteger()` method returns a random positive integer. The method takes an integer argument, `$length`, whose value defaults to 19. Supplying a value less than 1 or greater than 19 causes the method to throw a `\RangeException`. The method returns an integer whose total number of digits equals the value of `$length`.

## Examples

```php
$integer = SecureRandom::positiveInteger();    // 2562604524331120248 (19 digits long by default)
$integer = SecureRandom::positiveInteger(6);   // 197955 (6 digits long)
$integer = SecureRandom::positiveInteger(1);   // 7 (1 digit long)
$integer = SecureRandom::positiveInteger(0);   // Throws \RangeException
$integer = SecureRandom::positiveInteger(20);  // Throws \RangeException
```

## Meshachviktor\SecureRandom\SecureRandom::negativeInteger(int $length = 19) :int

The `negativeInteger()` method returns a random negative integer. The method takes an integer argument, `$length`, whose value defaults to 19. Supplying a value less than 1 or greater than 19 causes the method to throw a `\RangeException`. The method returns a negative integer whose total number of digits equals the value of `$length`.

## Examples

```php
$integer = SecureRandom::negativeInteger();    // -4259319387199823755 (19 digits long by default)
$integer = SecureRandom::negativeInteger(6);   // -808209 (6 digits long)
$integer = SecureRandom::negativeInteger(1);   // -5 (1 digit long)
$integer = SecureRandom::negativeInteger(0);   // Throws \RangeException
$integer = SecureRandom::negativeInteger(20);  // Throws \RangeException  
```

## Meshachviktor\SecureRandom\SecureRandom::integerBetween(int $min, int $max) :int

The `integerBetween()` method takes two integer arguments `$min` and `$max` and returns an integer between the range of `$min` and `$max` (inclusive). The method will throw `Meshachviktor\SecureRandom\Exception\ValueException` if the value of `$min` is greater than the value of `$max`.

## Examples

```php
$integer = SecureRandom::integerBetween(1, 10);        // 2
$integer = SecureRandom::integerBetween(1000, 5000);   // 4124`  
$integer = SecureRandom::integerBetween(-300, -100);   // -149
$integer = SecureRandom::integerBetween(-100, -300);   // Throws ValueException
```

## **Generating random strings**

The library provides some functions for generating random strings. There are three types of strings that can be generated.   

- Hexadecimal strings.
- Mixed case alphanumeric strings
- Version 4 UUIDs

When generating hexadecimal strings and alphanumric strings the corresponding methods take a single integer argument, `$length`, which determines how long the generated string should be. The value of `$lenth` defaults to 64. `\RangeException` is thrown is the value of `$length` is less than 1 or if it is greater than 64.   
The method that generates UUIDs takes no argument.

## Meshachviktor\SecureRandom\SecureRandom::hexadecimalString(int $length = 64) :string

Generates a hexadecimal string of given length.

## Examples

```php
$string = SecureRandom::hexadecimalString(8);     // 5bc3aac3
$string = SecureRandom::hexadecimalString(16);    // 6bc28f1fc391c386
$string = SecureRandom::hexadecimalString(0);     // Throws \RangeException
$string = SecureRandom::hexadecimalString(65);    // Throws \RangeException  
```

## Meshachviktor\SecureRandom\SecureRandom::alphanumericString(int $length = 64) :string

Generates an alphanumeric string of given length.

## Examples

```php
$string = SecureRandom::alphanumericString(8);     // fxbjwCmi
$string = SecureRandom::alphanumericString(16);    // 84XxE8OY7CKQg0na
$string = SecureRandom::alphanumericString(0);     // Throws \RangeException
$string = SecureRandom::alphanumericString(65);    // Throws \RangeException
```

## Meshachviktor\SecureRandom\SecureRandom::uuid() : string

Generates version 4 UUIDs.

## Examples

```php
$uuid = SecureRandom::uuid();     // 105f4516-e80c-4c32-b2fe-42dafa85d9cd
$uuid = SecureRandom::uuid();     // 54ef56c9-cae6-48c2-824f-6d8a2737a3d6   
```