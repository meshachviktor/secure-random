<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\GreaterThan;
use Meshachviktor\SecureRandom\SecureRandom;
use Meshachviktor\SecureRandom\Exception\ValueException;

final class FloatTest extends TestCase
{

    public function testFloat(): void
    {
        $this->assertIsFloat(SecureRandom::float());
        $this->assertIsFloat(SecureRandom::float(14));
        $this->assertIsFloat(SecureRandom::float(1));
        $this->assertIsFloat(SecureRandom::float(4));
    }

    public function testPositiveFloat(): void
    {
        $float = SecureRandom::positiveFloat();

        $this->assertIsFloat(SecureRandom::positiveFloat());
        $this->assertIsFloat(SecureRandom::positiveFloat(14));
        $this->assertIsFloat(SecureRandom::positiveFloat(1));
        $this->assertIsFloat(SecureRandom::positiveFloat(4));
        $this->assertThat($float, $this->greaterThan(0));
        $this->assertThat($float, $this->lessThan(1));
    }

    public function testNegativeFloat(): void
    {
        $float = SecureRandom::negativeFloat();

        $this->assertIsFloat(SecureRandom::negativeFloat());
        $this->assertIsFloat(SecureRandom::negativeFloat(14));
        $this->assertIsFloat(SecureRandom::negativeFloat(1));
        $this->assertIsFloat(SecureRandom::negativeFloat(4));
        $this->assertThat($float, $this->lessThan(0));
    }

    public function testFloatBetween(): void
    {
        $float = SecureRandom::floatBetween(0.2, 0.8);

        $this->assertIsFloat($float);
        $this->assertThat($float, $this->greaterThan(0.2));
        $this->assertThat($float, $this->lessThan(0.9));
    }

    public function testFloatException(): void
    {
        $this->expectException(RangeException::class);
        SecureRandom::float(0);
        SecureRandom::float(15);
    }

    public function testPositiveFloatException(): void
    {
        $this->expectException(RangeException::class);
        SecureRandom::positiveFloat(0);
        SecureRandom::positiveFloat(15);
    }

    public function testNegativeFloatException(): void
    {
        $this->expectException(RangeException::class);
        SecureRandom::negativeFloat(0);
        SecureRandom::negativeFloat(15);
    }

    public function testFloatBetweenRangeException(): void
    {
        $this->expectException(RangeException::class);
        SecureRandom::floatBetween(0.2, 0.9999999999999999);
        SecureRandom::floatBetween(1, 2.1);

    }

    public function testFloatBetweenValueException(): void
    {
        $this->expectException(ValueException::class);
        SecureRandom::floatBetween(0.13, 0.12);
    }

}