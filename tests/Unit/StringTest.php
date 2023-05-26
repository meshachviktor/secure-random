<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\GreaterThan;
use Meshachviktor\SecureRandom\SecureRandom;

final class StringTest extends TestCase
{

    public function testHexadecimalString(): void
    {

        $this->assertMatchesRegularExpression('/^[\da-f][\da-f]+[\da-f]$/', SecureRandom::hexadecimalString());
        $this->assertMatchesRegularExpression('/^[\da-f][\da-f]+[\da-f]$/', SecureRandom::hexadecimalString(64));
        $this->assertMatchesRegularExpression('/^[\da-f][\da-f]+[\da-f]$/', SecureRandom::hexadecimalString(32));
        $this->assertMatchesRegularExpression('/^[\da-f][\da-f]+[\da-f]$/', SecureRandom::hexadecimalString(16));
    }

    public function testAlphanumericString(): void
    {

        $this->assertMatchesRegularExpression('/^[\da-zA-Z][\da-zA-Z]+[\da-zA-Z]$/', SecureRandom::alphanumericString());
        $this->assertMatchesRegularExpression('/^[\da-zA-Z][\da-zA-Z]+[\da-zA-Z]$/', SecureRandom::alphanumericString(64));
        $this->assertMatchesRegularExpression('/^[\da-zA-Z][\da-zA-Z]+[\da-zA-Z]$/', SecureRandom::alphanumericString(32));
        $this->assertMatchesRegularExpression('/^[\da-zA-Z][\da-zA-Z]+[\da-zA-Z]$/', SecureRandom::alphanumericString(16));
    }

    public function testUuid(): void
    {

        $this->assertMatchesRegularExpression('/^[\da-f]{8}-[\da-f]{4}-[4][\da-f]{3}-[89ab][\da-f]{3}-[\da-f]{12}$/', SecureRandom::uuid());
    }

    public function testHexadecimalStringException(): void
    {

        $this->expectException(RangeException::class);
        SecureRandom::hexadecimalString(0);
        SecureRandom::hexadecimalString(65);
    }

    public function testAlphanumericStringException(): void
    {

        $this->expectException(RangeException::class);
        SecureRandom::alphanumericString(0);
        SecureRandom::alphanumericString(65);
    }

}