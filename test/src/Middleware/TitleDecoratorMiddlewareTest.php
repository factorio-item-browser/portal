<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Middleware\TitleDecoratorMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Helper\HeadTitle;

/**
 * The PHPUnit test of the TitleDecoratorMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\TitleDecoratorMiddleware
 */
class TitleDecoratorMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked head title helper.
     * @var HeadTitle&MockObject
     */
    protected $headTitleHelper;

    /**
     * The mocked translator.
     * @var TranslatorInterface&MockObject
     */
    protected $translator;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->headTitleHelper = $this->createMock(HeadTitle::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $middleware = new TitleDecoratorMiddleware($this->headTitleHelper, $this->translator);

        $this->assertSame($this->headTitleHelper, $this->extractProperty($middleware, 'headTitleHelper'));
        $this->assertSame($this->translator, $this->extractProperty($middleware, 'translator'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcess(): void
    {
        $translatedTitle = 'abc';

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

        $this->headTitleHelper->expects($this->exactly(2))
                              ->method('__call')
                              ->withConsecutive(
                                  [$this->identicalTo('setSeparator'), $this->identicalTo([' - '])],
                                  [$this->identicalTo('append'), $this->identicalTo(['abc'])]
                              );

        $this->translator->expects($this->once())
                         ->method('translate')
                         ->with($this->identicalTo('meta title'))
                         ->willReturn($translatedTitle);

        $middleware = new TitleDecoratorMiddleware($this->headTitleHelper, $this->translator);
        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }
}
