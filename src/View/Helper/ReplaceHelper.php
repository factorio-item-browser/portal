<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * A view helper for replacing placeholder variables in a string.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ReplaceHelper extends AbstractHelper
{
    /**
     * Replaces the specified variables in the string.
     * @param string $string
     * @param array|string[] $variables
     * @return string
     */
    public function __invoke(string $string, array $variables)
    {
        $preparedVariables = [];
        foreach ($variables as $name => $value) {
            $preparedVariables['%' . $name . '%'] = $value;
        }
        return str_replace(array_keys($preparedVariables), array_values($preparedVariables), $string);
    }
}