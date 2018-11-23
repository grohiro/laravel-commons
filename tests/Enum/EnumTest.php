<?php
namespace LaravelCommons\Tests\Enum;

use LaravelCommons\Tests\TestCase;
use LaravelCommons\Tests\Enum\Platform;

/**
 * Enum test
 */
class EnumTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Platform::init();
    }

    public function testValueOf()
    {
        $ios = Platform::valueOf(1);
        $this->assertEquals(1, $ios->id);
        $this->assertEquals('iOS', $ios->label);
        $this->assertEquals('ios', $ios->code);
    }

    public function testValueOfCode()
    {
        $android = Platform::valueOfCode('android');
        $this->assertEquals(2, $android->id);
        $this->assertEquals('Android', $android->label);
        $this->assertEquals('android', $android->code);
    }
}
