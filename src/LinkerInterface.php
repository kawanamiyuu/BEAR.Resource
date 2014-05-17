<?php
/**
 * This file is part of the BEAR.Resource package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

use Ray\Di\Di\ImplementedBy;

/**
 * Interface for resource link
 *
 * @ImplementedBy("BEAR\Resource\Linker")
 */
interface LinkerInterface
{
    /**
     * InvokerInterface link
     *
     * @param Request $request
     *
     * @return ResourceObject
     */
    public function invoke(AbstractRequest $request);

    /**
     * Set resource
     *
     * @param $resource $resource
     * @return void
     */
    public function setResource(ResourceInterface $resource);
}
