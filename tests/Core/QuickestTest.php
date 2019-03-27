<?php

namespace Quickest\Tests;

use PHPUnit\Framework\TestCase;
use Quickest\Core\Quickest;

class QuickestTest extends TestCase
{
    public function testQuickestClassCanBeInstanciated()
    {
        $this->assertInstanceOf(Quickest::class, new Quickest());
    }
}
