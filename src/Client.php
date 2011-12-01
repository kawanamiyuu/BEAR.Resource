<?php
/**
 * BEAR.Resource
 *
 * @package BEAR.Resource
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

use BEAR\Resource\Object as ResourceObject,
    BEAR\Resource\Adapter\App\Link as LikType;

/**
 * Resource client
 *
 * @package BEAR.Resource
 * @author  Akihito Koriyama <akihito.koriyama@gmail.com>
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @Scope("singleton")
 */
class Client implements Resource
{
    /**
     * Resource factory
     *
     * @var Factory
     */
    private $factory;

    /**
     * Resource request invoker
     *
     * @var Invoker
     */
    private $invoker;

    /**
     * Resource request
     *
     * @var Request
     */
    private $request;

    /**
     * Constructor
     *
     * @param Factory $factory resource object factory.
     * @param Invokable  $invoker resource request invoker
     * @param Request $request resource request
     *
     * @Inject
     */
    public function __construct(Factory $factory, Invokable $invoker, Request $request)
    {
        $this->factory = $factory;
        $this->invoker = $invoker;
        $this->newRequest = $request;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::newInstance()
     */
    public function newInstance($uri)
    {
        $instance = $this->factory->newInstance($uri);
        return $instance;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::object()
     */
    public function object($ro)
    {
        $this->request->ro = $ro;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::uri()
     */
    public function uri($uri)
    {
        if (is_string($uri)) {
            $this->request->ro = $this->newInstance($uri);
            return $this;
        }
        if ($uri instanceof Uri) {
            $this->request->ro = $this->newInstance($uri->uri);
            $this->withQuery($uri->query);
            return $this;
        }
        throw new Exception\InvalidUri;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::withQuery()
     */
    public function withQuery(array $query)
    {
        $this->request->query = $query;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::addQuery()
     */
    public function addQuery(array $query)
    {
        $this->request->query = array_merge($this->request->query, $query);
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::linkSelf()
     */
    public function linkSelf($linkKey)
    {
        $link = new LikType;
        $link->key = $linkKey;
        $link->type = LikType::SELF_LINK;
        $this->request->links[] = $link;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::linkNew()
     */
    public function linkNew($linkKey)
    {
        $link = new LikType;
        $link->key = $linkKey;
        $link->type = LikType::NEW_LINK;
        $this->request->links[] = $link;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::linkCrawl()
     */
    public function linkCrawl($linkKey)
    {
        $link = new LikType;
        $link->key = $linkKey;
        $link->type = LikType::CRAWL_LINK;
        $this->request->links[] = $link;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::request()
     */
    public function request()
    {
        if ($this->request->in === 'eager') {
            return $this->invoker->invoke($this->request);
        }
        return $this->request;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.Resource::__get($name)
     * @throws Exception\InvalidRequest
     */
    public function __get($name)
    {
        switch ($name) {
            case 'get':
            case 'post':
            case 'put':
            case 'delete':
            case 'head':
            case 'options':
                $this->request = clone $this->newRequest;
                $this->request->method = $name;
                return $this;
            case 'lazy':
            case 'eager':
                $this->request->in = $name;
                return $this;
            case 'poe':
            case 'csrf':
                $this->request->options[$name] = true;
                return $this;
            default:
                throw new Exception\BadRequest($name, 400);
        }
    }

    /**
     * Return requeset string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->request;
    }

}
