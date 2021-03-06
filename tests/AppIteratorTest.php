<?php

declare(strict_types=1);
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Resource;

use BEAR\Resource\Exception\ResourceDirException;
use PHPUnit\Framework\TestCase;

class AppIteratorTest extends TestCase
{
    /**
     * @var AppIterator
     */
    private $appIterator;

    protected function setUp()
    {
        $resourceDir = __DIR__ . '/Fake/Mock';
        $this->appIterator = new AppIterator($resourceDir);
    }

    public function testForEach()
    {
        foreach ($this->appIterator as $key => $meta) {
            $isValidUri = filter_var($meta->uri, FILTER_VALIDATE_URL);
            $this->assertTrue((bool) $isValidUri);
            $this->assertInstanceOf(Meta::class, $meta);
        }
    }

    public function testResourceDirException()
    {
        $this->expectException(ResourceDirException::class);
        new AppIterator('/invalid');
    }
}
