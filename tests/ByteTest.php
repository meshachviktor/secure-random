<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\GreaterThan;
use Meshachviktor\SecureRandom\SecureRandom;

final class ByteTest extends TestCase
{

    public function testByte(): void
    {
        $this->assertIsString(SecureRandom::bytes());
    }
    
    public function testByteException(): void
    {
        $this->expectException(RangeException::class);
        SecureRandom::bytes(-1);
        SecureRandom::bytes(65);
    }

}