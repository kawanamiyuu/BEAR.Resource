<?php
/**
 * This file is part of the *** package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

interface AnchorInterface
{
    /**
     * Return linked request with hyper reference
     *
     * @param string          $rel
     * @param AbstractRequest $request
     * @param array           $query
     *
     * @return mixed
     */
    public function href($rel, AbstractRequest $request, array $query);
}
