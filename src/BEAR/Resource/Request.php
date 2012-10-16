<?php
/**
 * This file is part of the BEAR.Resource package
 *
 * @package BEAR.Resource
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

use BEAR\Resource\Renderable;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Scope;

/**
 * Interface for resource adapter provider.
 *
 * @package BEAR.Resource
 *
 * @Scope("prototype")
 */
final class Request implements Requestable
{
    /**
     * object URI scheme
     *
     * @var string
     */
    const SCHEME_OBJECT = 'object';

    /**
     * URI
     *
     * @var string
     */
    public $uri;

    /**
     * Resource object
     *
     * @var \BEAR\Resource\AbstractObject
     */
    public $ro;

    /**
     * Method
     *
     * @var string
     */
    public $method = '';

    /**
     * Query
     *
     * @var array
     */
    public $query = [];

    /**
     * Options
     *
     * @var array
     */
    public $options = [];

    /**
     * Request option (eager or lazy)
     *
     * @var string
     */
    public $in;

    /**
     * Links
     *
     * @var array
     */
    public $links = [];

    /**
     * Request Result
     *
     * @var Object
     */
    private $result;

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Requestable::__construct()
     *
     * @Inject
     */
    public function __construct(InvokerInterface $invoker)
    {
        $this->invoker = $invoker;
    }

    public function set(Object $ro, $uri, $method, array $query)
    {
        $this->ro = $ro;
        $this->uri = $uri;
        $this->method = $method;
        $this->query = $query;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Requestable::__invoke()
     */
    public function __invoke(array $query = null)
    {
        if (!is_null($query)) {
            $this->query = array_merge($this->query, $query);
        }
        $result = $this->invoker->invoke($this);

        return $result;
    }

    /**
     * Render view
     *
     * @return string
     */
    public function __toString()
    {
        if (is_null($this->result)) {
            $this->result = $this->__invoke();
        }

        return (string)$this->result;
    }

    /**
     * To Request URI string
     *
     * @return string
     */
    public function toUri()
    {
        $query = http_build_query($this->query, null, '&', PHP_QUERY_RFC3986);
        $uri = $this->ro->uri;
        if (isset(parse_url($uri)['query'])) {
            $queryString = $uri;
        } else {
            $queryString = "{$uri}" . ($query ? '?' : '') . $query;
        }

        return $queryString;
    }

    /**
     * To Request URI string with request method
     *
     * @return string
     */
    public function toUriWithMethod()
    {
        return "{$this->method} " . $this->toUri();
    }
}
