<?php

namespace BEAR\Resource;

use Ray\Di\Definition;
use Ray\Di\Injector;

/**
 * Test class for BEAR.Resource.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    protected function setUp()
    {
        parent::setUp();
        $injector = Injector::create();
        $scheme = new SchemeCollection;
        $scheme->scheme('app')->host('self')->toAdapter(
            new Adapter\App($injector, 'testworld', 'ResourceObject')
        );
        $scheme->scheme('page')->host('self')->toAdapter(
            new Adapter\App($injector, 'testworld', 'Page')
        );
        $scheme->scheme('nop')->host('self')->toAdapter(new Adapter\Nop);
        $scheme->scheme('prov')->host('self')->toAdapter(new Adapter\Prov);
        $this->factory = new Factory($scheme);
    }

    public function test_NewFactory()
    {
        $this->assertInstanceOf('\BEAR\Resource\Factory', $this->factory);
    }

    public function test_newInstanceNop()
    {
        $instance = $this->factory->newInstance('nop://self/path/to/dummy');
        $this->assertInstanceOf('\BEAR\Resource\Adapter\Nop', $instance);
    }

    public function test_newInstanceWithProvider()
    {
        $instance = $this->factory->newInstance('prov://self/path/to/dummy');
        $this->assertInstanceOf('\stdClass', $instance);
    }

    /**
     * @expectedException \BEAR\Resource\Exception\Scheme
     */
    public function test_newInstanceScheme()
    {
        $this->factory->newInstance('bad://self/news');
    }

    /**
     * @expectedException \BEAR\Resource\Exception\Scheme
     */
    public function test_newInstanceSchemes()
    {
        $this->factory->newInstance('app://invalid_host/news');
    }


    public function test_newInstanceApp()
    {
        $instance = $this->factory->newInstance('app://self/news');
        $this->assertInstanceOf('testworld\ResourceObject\news', $instance);
    }

    public function test_newInstancePage()
    {
        $instance = $this->factory->newInstance('page://self/news');
        $this->assertInstanceOf('testworld\Page\news', $instance);
    }

    /**
     * @expectedException \BEAR\Resource\Exception\Uri
     */
    public function test_invaliUri()
    {
        $this->factory->newInstance('invalid_uri');
    }

}