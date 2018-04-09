<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Blast\BaseUrl\BasePathViewHelper;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for creating versioned asset paths.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AssetPathHelper extends AbstractHelper
{
    /**
     * The version to use for the assets.
     * @var string
     */
    protected $version;

    /**
     * The base path helper.
     * @var BasePathViewHelper
     */
    protected $basePathHelper;

    /**
     * Initializes the asset path helper.
     * @param string $version
     * @param BasePathViewHelper $basePathHelper
     */
    public function __construct(string $version, BasePathViewHelper $basePathHelper)
    {
        $this->version = $version;
        $this->basePathHelper = $basePathHelper;
    }

    /**
     * Transforms the specified path to a versioned asset path.
     * @param string $path
     * @return string
     */
    public function __invoke(string $path): string
    {
        $path = ($this->basePathHelper)($path);
        $parts = explode('.', $path);
        array_splice($parts, -1, 0, $this->version);
        return implode('.', $parts);
    }
}