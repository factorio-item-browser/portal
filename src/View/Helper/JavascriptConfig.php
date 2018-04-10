<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\Expressive\ZendView\UrlHelper;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for injecting an additional config into the Javascript.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class JavascriptConfig extends AbstractHelper
{
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
     * @param string $settingsHash
     * @param UrlHelper $urlHelper
     */
    public function __construct(string $settingsHash/*, UrlHelper $urlHelper*/)
    {
        $this->settingsHash = $settingsHash;
//        $this->urlHelper = $urlHelper;
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
        $this->view->render('helper::javascriptConfig/render', ['config' => $this->config]);
        return $this;
    }

    /**
     * Adds the common config values which is always present.
     * @return $this
     */
    protected function addCommonConfig()
    {
        $this->add([
            'settingsHash' => $this->settingsHash
       ]);
// @todo
//        $this->add([
//                       'cssLoader' => [
//                           'url' => $this->urlHelper->__invoke(RouteNames::ICONS, ['hash' => $this->settingsHash]),
//                       ],
//                       'settingsHash' => $this->settingsHash,
//                       'sidebar' => [
//                           'numberOfUnpinnedEntities' => SidebarEntityService::NUMBER_OF_UNPINNED_ENTITIES,
//                           'urls' => [
//                               'pin' => $this->urlHelper->__invoke(RouteNames::SIDEBAR_PIN, ['id' => '--id--']),
//                               'unpin' => $this->urlHelper->__invoke(RouteNames::SIDEBAR_UNPIN, ['id' => '--id--']),
//                           ]
//                       ]
//                   ]);
        return $this;
    }
}