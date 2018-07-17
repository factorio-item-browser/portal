<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\View\Helper\HeadLink;

/**
 * The extension of the head link view helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class HeadLinkHelper extends HeadLink
{
    /**
     * Appends a relation link to the head links.
     * @param string $relation
     * @param string $href
     * @return $this
     */
    public function appendRelation($relation, $href)
    {
        $this->append($this->createData(
            [
                'rel' => $relation,
                'href' => $href
            ]
        ));
        return $this;
    }
}
