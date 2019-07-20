<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Blast\BaseUrl\BasePathHelper;
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
     * The base path helper.
     * @var BasePathHelper
     */
    protected $basePathHelper;

    /**
     * The version to use for the assets.
     * @var string
     */
    protected $version;

    /**
     * Initializes the asset path helper.
     * @param BasePathHelper $basePathHelper
     * @param string $version
     */
    public function __construct(BasePathHelper $basePathHelper, string $version)
    {
        $this->basePathHelper = $basePathHelper;
        $this->version = $version;
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
