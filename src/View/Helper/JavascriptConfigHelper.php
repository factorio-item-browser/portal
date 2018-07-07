<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for injecting an additional config into the Javascript.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class JavascriptConfigHelper extends AbstractHelper
{
    /**
     * The asset path helper.
     * @var AssetPathHelper
     */
    protected $assetPathHelper;

    /**
     * The settings hash.
     * @var string
     */
    protected $settingsHash;

    /**
     * The URL helper.
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * The config to inject.
     * @var array
     */
    protected $config = [];

    /**
     * Initializes the view helper.
     * @param AssetPathHelper $assetPathHelper
     * @param string $settingsHash
     * @param UrlHelper $urlHelper
     */
    public function __construct(AssetPathHelper $assetPathHelper, string $settingsHash, UrlHelper $urlHelper)
    {
        $this->assetPathHelper = $assetPathHelper;
        $this->settingsHash = $settingsHash;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Adds some config values to the config to be injected to the Javascript.
     * @param array $config
     * @return $this
     */
    public function add(array $config)
    {
        $this->config = ArrayUtils::merge($this->config, $config);
        return $this;
    }

    /**
     * Renders the template to inject the config into the Javascript.
     * @return $this
     */
    public function render()
    {
        $this->addCommonConfig();
        $this->view->render('helper::javascriptConfig/script', ['config' => $this->config]);
        return $this;
    }

    /**
     * Adds the common config values which is always present.
     * @return $this
     */
    protected function addCommonConfig()
    {
        $this->add([
            'iconManager' => [
                'url' => $this->urlHelper->generate(RouteNames::ICONS)
            ],
            'script' => [
                'default' => $this->assetPathHelper->__invoke('asset/js/main.min.js'),
                'fallback' => $this->assetPathHelper->__invoke('asset/js/main.es5.min.js')
            ],
            'settingsHash' => $this->settingsHash,
            'sidebar' => [
                'numberOfUnpinnedEntities' => Config::SIDEBAR_UNPINNED_ENTITIES,
                'pinnedUrl' => $this->urlHelper->generate(RouteNames::SIDEBAR_PINNED)
            ]
        ]);
        return $this;
    }
}