<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class PhoreArrayTest extends TestCase
{
    public function testIndexOfFindsFirstElement(): void
    {
        $this->assertEquals(0, phore_array(['first', 'second'])->indexOf('first'));
    }

    public function testIndexOfReturnsMinusOneWhenValueIsMissing(): void
    {
        $this->assertEquals(-1, phore_array(['first', 'second'])->indexOf('missing'));
    }
}
