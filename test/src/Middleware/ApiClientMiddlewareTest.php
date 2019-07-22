<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Middleware\ApiClientMiddleware;
use FactorioItemBrowser\Portal\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The PHPUnit test of the ApiClientMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\ApiClientMiddleware
 */
class ApiClientMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked api client.
     * @var ApiClientInterface&MockObject
     */
    protected $apiClient;

    /**
     * The mocked template renderer.
     * @var TemplateRendererInterface&MockObject
     */
    protected $templateRenderer;

    /**
     * The mocked user service.
     * @var UserService&MockObject
     */
    protected $userService;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
        $this->userService = $this->createMock(UserService::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $middleware = new ApiClientMiddleware($this->apiClient, $this->templateRenderer, $this->userService);

        $this->assertSame($this->apiClient, $this->extractProperty($middleware, 'apiClient'));
        $this->assertSame($this->templateRenderer, $this->extractProperty($middleware, 'templateRenderer'));
        $this->assertSame($this->userService, $this->extractProperty($middleware, 'userService'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcess(): void
    {
        /* @var User&MockObject $currentUser */
        $currentUser = $this->createMock(User::class);
        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request))
                ->willReturn($response);

        $this->userService->expects($this->once())
                          ->method('getCurrentUser')
                          ->willReturn($currentUser);

        /* @var ApiClientMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(ApiClientMiddleware::class)
                           ->setMethods(['initializeApiClient', 'createErrorResponse', 'finalizeApiClient'])
                           ->setConstructorArgs([$this->apiClient, $this->templateRenderer, $this->userService])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('initializeApiClient')
                   ->with($this->identicalTo($currentUser));
        $middleware->expects($this->never())
                   ->method('createErrorResponse');
        $middleware->expects($this->once())
                   ->method('finalizeApiClient')
                   ->with($this->identicalTo($currentUser));

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Tests the process method.
     * @throws ReflectionException
     * @covers ::process
     */
    public function testProcessWithError(): void
    {
        /* @var User&MockObject $currentUser */
        $currentUser = $this->createMock(User::class);
        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $errorResponse */
        $errorResponse = $this->createMock(ResponseInterface::class);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request))
                ->willThrowException($this->createMock(ApiClientException::class));

        $this->userService->expects($this->once())
                          ->method('getCurrentUser')
                          ->willReturn($currentUser);

        /* @var ApiClientMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(ApiClientMiddleware::class)
                           ->setMethods(['initializeApiClient', 'createErrorResponse', 'finalizeApiClient'])
                           ->setConstructorArgs([$this->apiClient, $this->templateRenderer, $this->userService])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('initializeApiClient')
                   ->with($this->identicalTo($currentUser));
        $middleware->expects($this->once())
                   ->method('createErrorResponse')
                   ->willReturn($errorResponse);
        $middleware->expects($this->never())
                   ->method('finalizeApiClient');

        $result = $middleware->process($request, $handler);

        $this->assertSame($errorResponse, $result);
    }

    /**
     * Tests the initializeApiClient method.
     * @throws ReflectionException
     * @covers ::initializeApiClient
     */
    public function testInitializeApiClient(): void
    {
        $locale = 'abc';
        $enabledModNames = ['def', 'ghi'];
        $authorizationToken = 'jkl';

        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);
        $user->expects($this->once())
             ->method('getLocale')
             ->willReturn($locale);
        $user->expects($this->once())
             ->method('getEnabledModNames')
             ->willReturn($enabledModNames);
        $user->expects($this->once())
             ->method('getApiAuthorizationToken')
             ->willReturn($authorizationToken);

        $this->apiClient->expects($this->once())
                        ->method('setLocale')
                        ->with($this->identicalTo($locale));
        $this->apiClient->expects($this->once())
                        ->method('setEnabledModNames')
                        ->with($this->identicalTo($enabledModNames));
        $this->apiClient->expects($this->once())
                        ->method('setAuthorizationToken')
                        ->with($this->identicalTo($authorizationToken));

        $middleware = new ApiClientMiddleware($this->apiClient, $this->templateRenderer, $this->userService);
        $this->invokeMethod($middleware, 'initializeApiClient', $user);
    }

    /**
     * Tests the finalizeApiClient method.
     * @throws ReflectionException
     * @covers ::finalizeApiClient
     */
    public function testFinalizeApiClient(): void
    {
        $authorizationToken = 'abc';

        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);
        $user->expects($this->once())
             ->method('setApiAuthorizationToken')
             ->with($this->identicalTo($authorizationToken))
             ->willReturnSelf();

        $this->apiClient->expects($this->once())
                        ->method('getAuthorizationToken')
                        ->willReturn($authorizationToken);

        $middleware = new ApiClientMiddleware($this->apiClient, $this->templateRenderer, $this->userService);
        $this->invokeMethod($middleware, 'finalizeApiClient', $user);
    }

    /**
     * Tests the createErrorResponse method.
     * @throws ReflectionException
     * @covers ::createErrorResponse
     */
    public function testCreateErrorResponse(): void
    {
        $content = 'abc';

        $this->templateRenderer->expects($this->once())
                               ->method('render')
                               ->with($this->identicalTo('error::503'))
                               ->willReturn($content);

        $middleware = new ApiClientMiddleware($this->apiClient, $this->templateRenderer, $this->userService);

        $result = $this->invokeMethod($middleware, 'createErrorResponse');

        $this->assertInstanceOf(HtmlResponse::class, $result);
        /* @var HtmlResponse $result */
        $this->assertSame(503, $result->getStatusCode());
        $this->assertSame($content, $result->getBody()->getContents());
    }
}
