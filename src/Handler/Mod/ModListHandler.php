<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\Mod;
use FactorioItemBrowser\Api\Client\Request\Mod\ModListRequest;
use FactorioItemBrowser\Api\Client\Response\Mod\ModListResponse;
use FactorioItemBrowser\Portal\Session\Container\ModListSessionContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of the mod list.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ModListHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The mod list session container.
     * @var ModListSessionContainer
     */
    protected $modListSessionContainer;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param ModListSessionContainer $modListSessionContainer
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        Client $apiClient,
        ModListSessionContainer $modListSessionContainer,
        TemplateRendererInterface $templateRenderer
    )
    {
        $this->apiClient = $apiClient;
        $this->modListSessionContainer = $modListSessionContainer;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $modListRequest = new ModListRequest();
        /* @var ModListResponse $modListResponse */
        $modListResponse = $this->apiClient->send($modListRequest);

        $response = new HtmlResponse($this->templateRenderer->render('portal::mod/list', [
            'mods' => $this->sortMods($modListResponse->getMods()),
            'showSuccessMessage' => $this->modListSessionContainer->getShowSuccessMessage(),
            'uploadErrorMessage' => $this->modListSessionContainer->getUploadErrorMessage(),
            'missingModNames' => $this->modListSessionContainer->getMissingModNames()
        ]));
        $this->modListSessionContainer->reset();
        return $response;
    }

    /**
     * Sorts the mods after name, preferring the base mod.
     * @param array|Mod[] $mods
     * @return array|Mod[]
     */
    protected function sortMods(array $mods): array
    {
        usort($mods, function (Mod $left, Mod $right): int {
            if ($left->getName() === 'base') {
                $result = -1;
            } elseif ($right->getName() === 'base') {
                $result = 1;
            } else {
                $result = strtolower($left->getName()) <=> strtolower($right->getName());
            }
            return $result;
        });
        return array_values($mods);
    }
}