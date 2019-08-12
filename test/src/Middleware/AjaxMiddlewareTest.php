<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Constant\Attribute;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Middleware\AjaxMiddleware;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use FactorioItemBrowser\Portal\View\Helper\SidebarHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\View\Helper\HeadTitle;

/**
 * The PHPUnit test of the AjaxMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\AjaxMiddleware
 */
class AjaxMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked head title helper.
     * @var HeadTitle&MockObject
     */
    protected $headTitleHelper;

    /**
     * The mocked layout params helper.
     * @var LayoutParamsHelper&MockObject
     */
    protected $layoutParamsHelper;

    /**
     * The mocked sidebar helper.
     * @var SidebarHelper&MockObject
     */
    protected $sidebarHelper;

    /**
     * The mocked template renderer.
     * @var TemplateRendererInterface&MockObject
     */
    protected $templateRenderer;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->headTitleHelper = $this->createMock(HeadTitle::class);
        $this->layoutParamsHelper = $this->createMock(LayoutParamsHelper::class);
        $this->sidebarHelper = $this->createMock(SidebarHelper::class);
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $middleware = new AjaxMiddleware(
            $this->headTitleHelper,
            $this->layoutParamsHelper,
            $this->sidebarHelper,
            $this->templateRenderer
        );

        $this->assertSame($this->headTitleHelper, $this->extractProperty($middleware, 'headTitleHelper'));
        $this->assertSame($this->layoutParamsHelper, $this->extractProperty($middleware, 'layoutParamsHelper'));
        $this->assertSame($this->sidebarHelper, $this->extractProperty($middleware, 'sidebarHelper'));
        $this->assertSame($this->templateRenderer, $this->extractProperty($middleware, 'templateRenderer'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcess(): void
    {
        $isAjaxRequest = true;

        /* @var ServerRequestInterface&MockObject $request2 */
        $request2 = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response1 */
        $response1 = $this->createMock(ResponseInterface::class);
        /* @var ResponseInterface&MockObject $response2 */
        $response2 = $this->createMock(ResponseInterface::class);

        /* @var ServerRequestInterface&MockObject $request1 */
        $request1 = $this->createMock(ServerRequestInterface::class);
        $request1->expects($this->once())
                 ->method('withAttribute')
                 ->with($this->identicalTo(Attribute::REQUEST_AJAX), $this->isTrue())
                 ->willReturn($request2);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request2))
                ->willReturn($response1);

        $this->templateRenderer->expects($this->once())
                               ->method('addDefaultParam')
                               ->with(
                                   $this->identicalTo(TemplateRendererInterface::TEMPLATE_ALL),
                                   $this->identicalTo('layout'),
                                   $this->isFalse()
                               );

        /* @var AjaxMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(AjaxMiddleware::class)
                           ->setMethods(['isAjaxRequest', 'prepareAjaxResponse'])
                           ->setConstructorArgs([
                               $this->headTitleHelper,
                               $this->layoutParamsHelper,
                               $this->sidebarHelper,
                               $this->templateRenderer
                           ])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('isAjaxRequest')
                   ->with($this->identicalTo($request1))
                   ->willReturn($isAjaxRequest);
        $middleware->expects($this->once())
                   ->method('prepareAjaxResponse')
                   ->with($this->identicalTo($response1))
                   ->willReturn($response2);

        $result = $middleware->process($request1, $handler);

        $this->assertSame($response2, $result);
    }

    /**
     * Tests the process method.
     * @throws ReflectionException
     * @covers ::process
     */
    public function testProcessWithJsonResponse(): void
    {
        $isAjaxRequest = true;

        /* @var ServerRequestInterface&MockObject $request2 */
        $request2 = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(JsonResponse::class);

        /* @var ServerRequestInterface&MockObject $request1 */
        $request1 = $this->createMock(ServerRequestInterface::class);
        $request1->expects($this->once())
                 ->method('withAttribute')
                 ->with($this->identicalTo(Attribute::REQUEST_AJAX), $this->isTrue())
                 ->willReturn($request2);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request2))
                ->willReturn($response);

        $this->templateRenderer->expects($this->once())
                               ->method('addDefaultParam')
                               ->with(
                                   $this->identicalTo(TemplateRendererInterface::TEMPLATE_ALL),
                                   $this->identicalTo('layout'),
                                   $this->isFalse()
                               );

        /* @var AjaxMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(AjaxMiddleware::class)
                           ->setMethods(['isAjaxRequest', 'prepareAjaxResponse'])
                           ->setConstructorArgs([
                               $this->headTitleHelper,
                               $this->layoutParamsHelper,
                               $this->sidebarHelper,
                               $this->templateRenderer
                           ])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('isAjaxRequest')
                   ->with($this->identicalTo($request1))
                   ->willReturn($isAjaxRequest);
        $middleware->expects($this->never())
                   ->method('prepareAjaxResponse');

        $result = $middleware->process($request1, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Tests the process method.
     * @throws ReflectionException
     * @covers ::process
     */
    public function testProcessWithoutAjaxRequest(): void
    {
        $isAjaxRequest = false;

        /* @var ServerRequestInterface&MockObject $request2 */
        $request2 = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        /* @var ServerRequestInterface&MockObject $request1 */
        $request1 = $this->createMock(ServerRequestInterface::class);
        $request1->expects($this->once())
                 ->method('withAttribute')
                 ->with($this->identicalTo(Attribute::REQUEST_AJAX), $this->isFalse())
                 ->willReturn($request2);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request2))
                ->willReturn($response);

        $this->templateRenderer->expects($this->never())
                               ->method('addDefaultParam');

        /* @var AjaxMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(AjaxMiddleware::class)
                           ->setMethods(['isAjaxRequest', 'prepareAjaxResponse'])
                           ->setConstructorArgs([
                               $this->headTitleHelper,
                               $this->layoutParamsHelper,
                               $this->sidebarHelper,
                               $this->templateRenderer
                           ])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('isAjaxRequest')
                   ->with($this->identicalTo($request1))
                   ->willReturn($isAjaxRequest);
        $middleware->expects($this->never())
                   ->method('prepareAjaxResponse');

        $result = $middleware->process($request1, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Provides the data for the isAjaxRequest test.
     * @return array
     */
    public function provideIsAjaxRequest(): array
    {
        return [
            [['context' => 'json', 'abc' => 'def'], true],
            [['abc' => 'def'], false],
            [null, false],
        ];
    }

    /**
     * Tests the isAjaxRequest method.
     * @param mixed $body
     * @param bool $expectedResult
     * @throws ReflectionException
     * @covers ::isAjaxRequest
     * @dataProvider provideIsAjaxRequest
     */
    public function testIsAjaxRequest($body, bool $expectedResult): void
    {
        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
                ->method('getParsedBody')
                ->willReturn($body);

        $middleware = new AjaxMiddleware(
            $this->headTitleHelper,
            $this->layoutParamsHelper,
            $this->sidebarHelper,
            $this->templateRenderer
        );

        $result = $this->invokeMethod($middleware, 'isAjaxRequest', $request);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * Tests the prepareAjaxResponse method.
     * @throws ReflectionException
     * @covers ::prepareAjaxResponse
     */
    public function testPrepareAjaxResponse(): void
    {
        $statusCode = 123;
        $headers = ['abc' => ['def', 'ghi']];
        $contents = 'jkl';
        $settingsHash = 'mno';
        $title = 'pqr';
        $bodyClass = 'stu';
        $searchQuery = 'vwx';
        $renderedEntity = 'yza';

        $expectedPayload = [
            'content' => $contents,
            'settingsHash' => $settingsHash,
            'title' => $title,
            'bodyClass' => $bodyClass,
            'searchQuery' => $searchQuery,
            'newSidebarEntity' => $renderedEntity,
        ];

        /* @var SidebarEntity&MockObject $sidebarEntity */
        $sidebarEntity = $this->createMock(SidebarEntity::class);

        /* @var StreamInterface&MockObject $body */
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())
             ->method('getContents')
             ->willReturn($contents);

        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
                 ->method('getBody')
                 ->willReturn($body);
        $response->expects($this->once())
                 ->method('getStatusCode')
                 ->willReturn($statusCode);
        $response->expects($this->once())
                 ->method('getHeaders')
                 ->willReturn($headers);

        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getSettingsHash')
                                 ->willReturn($settingsHash);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getBodyClass')
                                 ->willReturn($bodyClass);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getSearchQuery')
                                 ->willReturn($searchQuery);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getNewSidebarEntity')
                                 ->willReturn($sidebarEntity);

        $this->headTitleHelper->expects($this->once())
                              ->method('renderTitle')
                              ->willReturn($title);

        $this->sidebarHelper->expects($this->once())
                            ->method('renderEntity')
                            ->with($this->identicalTo($sidebarEntity))
                            ->willReturn($renderedEntity);

        $middleware = new AjaxMiddleware(
            $this->headTitleHelper,
            $this->layoutParamsHelper,
            $this->sidebarHelper,
            $this->templateRenderer
        );

        $result = $this->invokeMethod($middleware, 'prepareAjaxResponse', $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
        /* @var JsonResponse $result */
        $this->assertSame($statusCode, $result->getStatusCode());
        $this->assertEquals($headers['abc'], $result->getHeaders()['abc']);
        $this->assertEquals($expectedPayload, $result->getPayload());
    }

    /**
     * Tests the prepareAjaxResponse method.
     * @throws ReflectionException
     * @covers ::prepareAjaxResponse
     */
    public function testPrepareAjaxResponseWithMinimalResponse(): void
    {
        $statusCode = 123;
        $headers = ['abc' => ['def', 'ghi']];
        $contents = 'jkl';
        $settingsHash = 'mno';
        $title = 'pqr';
        $bodyClass = '';
        $searchQuery = '';

        $expectedPayload = [
            'content' => $contents,
            'settingsHash' => $settingsHash,
            'title' => $title,
        ];

        /* @var StreamInterface&MockObject $body */
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())
             ->method('getContents')
             ->willReturn($contents);

        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
                 ->method('getBody')
                 ->willReturn($body);
        $response->expects($this->once())
                 ->method('getStatusCode')
                 ->willReturn($statusCode);
        $response->expects($this->once())
                 ->method('getHeaders')
                 ->willReturn($headers);

        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getSettingsHash')
                                 ->willReturn($settingsHash);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getBodyClass')
                                 ->willReturn($bodyClass);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getSearchQuery')
                                 ->willReturn($searchQuery);
        $this->layoutParamsHelper->expects($this->atLeastOnce())
                                 ->method('getNewSidebarEntity')
                                 ->willReturn(null);

        $this->headTitleHelper->expects($this->once())
                              ->method('renderTitle')
                              ->willReturn($title);

        $this->sidebarHelper->expects($this->never())
                            ->method('renderEntity');

        $middleware = new AjaxMiddleware(
            $this->headTitleHelper,
            $this->layoutParamsHelper,
            $this->sidebarHelper,
            $this->templateRenderer
        );

        $result = $this->invokeMethod($middleware, 'prepareAjaxResponse', $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
        /* @var JsonResponse $result */
        $this->assertSame($statusCode, $result->getStatusCode());
        $this->assertEquals($headers['abc'], $result->getHeaders()['abc']);
        $this->assertEquals($expectedPayload, $result->getPayload());
    }
}
