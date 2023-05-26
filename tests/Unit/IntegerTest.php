<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\GreaterThan;
use Meshachviktor\SecureRandom\SecureRandom;
use Meshachviktor\SecureRandom\Exception\ValueException;

final class IntegerTest extends TestCase
{

    public function testInteger(): void
    {

        $this->assertIsInt(SecureRandom::integer());
    }

    public function testPositiveInteger(): void
    {
        $integer = SecureRandom::positiveInteger();

        $this->assertIsInt(SecureRandom::positiveInteger());
        $this->assertIsInt(SecureRandom::positiveInteger(19));
        $this->assertIsInt(SecureRandom::positiveInteger(1));
        $this->assertIsInt(SecureRandom::positiveInteger(4));
        $this->assertThat($integer, $this->greaterThan(-1));
    }

    public function testNegativeInteger(): void
    {
        $integer = SecureRandom::negativeInteger();

        $this->assertIsInt(SecureRandom::negativeInteger());
        $this->assertIsInt(SecureRandom::negativeInteger(19));
        $this->assertIsInt(SecureRandom::negativeInteger(1));
        $this->assertIsInt(SecureRandom::negativeInteger(4));
        $this->assertThat($integer, $this->lessThan(0));
    }

    public function testIntegerBetween(): void
    {

        $this->assertIsInt(SecureRandom::integerBetween(1, 10));
        $this->assertIsInt(SecureRandom::integerBetween(PHP_INT_MIN, PHP_INT_MAX));
        $this->assertIsInt(SecureRandom::integerBetween(-2000, 8000));
        $this->assertIsInt(SecureRandom::integerBetween(4, 4400));
    }

    public function testPositiveIntegerException(): void
    {

        $this->expectException(RangeException::class);
        SecureRandom::positiveInteger(0);
        SecureRandom::positiveInteger(20);
    }

    public function testNegativeIntegerException(): void
    {

        $this->expectException(RangeException::class);
        SecureRandom::negativeInteger(0);
        SecureRandom::negativeInteger(20);
    }

    public function testIntegerBetweenValueException(): void
    {

        $this->expectException(ValueException::class);
        SecureRandom::integerBetween(100, 10);
    }

}