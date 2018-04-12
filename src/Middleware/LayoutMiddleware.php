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
 * The middleware handling the layout.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LayoutMiddleware implements MiddlewareInterface
{
    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

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
     * Initializes the middleware.
     * @param TemplateRendererInterface $templateRenderer
     * @param HeadTitle $headTitleHelper
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param SidebarHelper $sidebarHelper
     */
    public function __construct(
        TemplateRendererInterface $templateRenderer,
        HeadTitle $headTitleHelper,
        LayoutParamsHelper $layoutParamsHelper,
        SidebarHelper $sidebarHelper
    )
    {
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
        }
        $this->prepareTitle();

        $response = $handler->handle($request);

        if ($isAjaxRequest) {
            $response = $this->prepareAjaxResponse($response);
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
     * Prepares the title of the page.
     * @return $this
     */
    protected function prepareTitle()
    {
        $this->headTitleHelper->setSeparator(' - ');
        $this->headTitleHelper->append('Factorio Item Browser');
        return $this;
    }

    /**
     * Prepares the response of the AJAX request.
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function prepareAjaxResponse(ResponseInterface $response): ResponseInterface
    {
        if (!$response instanceof JsonResponse) {
            $responseData = [
                'content' => $response->getBody()->getContents(),
                'title' => trim($this->headTitleHelper->renderTitle()),
                // @todo Settings hash
            ];

            if (strlen($this->layoutParamsHelper->getBodyClass()) > 0) {
                $responseData['bodyClass'] = $this->layoutParamsHelper->getBodyClass();
            }
            if (strlen($this->layoutParamsHelper->getSearchQuery()) > 0) {
                $responseData['searchQuery'] = $this->layoutParamsHelper->getSearchQuery();
            }
            if ($this->layoutParamsHelper->getNewSidebarEntity() instanceof SidebarEntity) {
                $responseData['newSidebarEntity']
                    = $this->sidebarHelper->renderEntity($this->layoutParamsHelper->getNewSidebarEntity());
            }

            $response = new JsonResponse($responseData, $response->getStatusCode(), $response->getHeaders());
        }
        return $response;
    }
}