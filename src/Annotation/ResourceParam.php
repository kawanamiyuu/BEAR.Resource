<?php
/**
 * This file is part of the BEAR.Resource package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class ResourceParam
{
    /**
     * @var string
     */
    public $param;

    /**
     * @var string
     */
    public $uri;
}
