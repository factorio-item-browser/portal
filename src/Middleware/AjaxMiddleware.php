<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Constant\Attribute;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use FactorioItemBrowser\Portal\View\Helper\SidebarHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\View\Helper\HeadTitle;

/**
 * The middleware handling incoming AJAX requests.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AjaxMiddleware implements MiddlewareInterface
{
    /**
     * The head title helper.
     * @var HeadTitle
     */
    protected $headTitleHelper;

    /**
     * The layout params helper.
     * @var LayoutParamsHelper
     */
    protected $layoutParamsHelper;

    /**
     * The sidebar helper.
     * @var SidebarHelper
     */
    protected $sidebarHelper;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the middleware.
     * @param HeadTitle $headTitleHelper
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param SidebarHelper $sidebarHelper
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        HeadTitle $headTitleHelper,
        LayoutParamsHelper $layoutParamsHelper,
        SidebarHelper $sidebarHelper,
        TemplateRendererInterface $templateRenderer
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->headTitleHelper = $headTitleHelper;
        $this->layoutParamsHelper = $layoutParamsHelper;
        $this->sidebarHelper = $sidebarHelper;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isAjaxRequest = $this->isAjaxRequest($request);
        $request = $request->withAttribute(Attribute::REQUEST_AJAX, $isAjaxRequest);

        if ($isAjaxRequest) {
            $this->templateRenderer->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'layout', false);
            $response = $handler->handle($request);
            if (!$response instanceof JsonResponse) {
                $response = $this->prepareAjaxResponse($response);
            }
        } else {
            $response = $handler->handle($request);
        }

        return $response;
    }

    /**
     * Returns whether the request is from an AJAX context.
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function isAjaxRequest(ServerRequestInterface $request): bool
    {
        $body = $request->getParsedBody();
        return is_array($body) && ($body['context'] ?? '') === 'json';
    }

    /**
     * Prepares the response of the AJAX request.
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function prepareAjaxResponse(ResponseInterface $response): ResponseInterface
    {
        $responseData = [
            'content' => $response->getBody()->getContents(),
            'settingsHash' => $this->layoutParamsHelper->getSettingsHash(),
            'title' => trim($this->headTitleHelper->renderTitle()),
        ];

        if ($this->layoutParamsHelper->getBodyClass() !== '') {
            $responseData['bodyClass'] = $this->layoutParamsHelper->getBodyClass();
        }
        if ($this->layoutParamsHelper->getSearchQuery() !== '') {
            $responseData['searchQuery'] = $this->layoutParamsHelper->getSearchQuery();
        }
        if ($this->layoutParamsHelper->getNewSidebarEntity() instanceof SidebarEntity) {
            $responseData['newSidebarEntity']
                = $this->sidebarHelper->renderEntity($this->layoutParamsHelper->getNewSidebarEntity());
        }

        return new JsonResponse($responseData, $response->getStatusCode(), $response->getHeaders());
    }
}
