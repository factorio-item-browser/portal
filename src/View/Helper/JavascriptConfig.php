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
class JavascriptConfig extends AbstractHelper
{
    /**
     * The version to use for the assets.
     * @var string
     */
    protected $version;

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
     * @param string $version
     * @param string $settingsHash
     * @param UrlHelper $urlHelper
     */
    public function __construct(string $version, string $settingsHash, UrlHelper $urlHelper)
    {
        $this->version = $version;
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
            'settingsHash' => $this->settingsHash,
            'sidebar' => [
                'numberOfUnpinnedEntities' => Config::SIDEBAR_UNPINNED_ENTITIES,
                'urls' => [
                    'pin' => $this->urlHelper->generate(RouteNames::SIDEBAR_PIN, ['id' => 1234]),
                    'unpin' => $this->urlHelper->generate(RouteNames::SIDEBAR_UNPIN, ['id' => 1234])
                ]
            ],
            'version' => $this->version
        ]);
        return $this;
    }
}